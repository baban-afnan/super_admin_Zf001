<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\AgentService;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceField;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ValidationController extends Controller
{
    /**
     * List validation services with filters and pagination
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        // Base query filtering by service_type
        $query = AgentService::query()
            ->select('agent_services.*', 'users.email as user_email')
            ->join('users', 'agent_services.user_id', '=', 'users.id')
            ->whereIn('agent_services.service_type', ['NIN_VALIDATION', 'validation', 'nin_validation']);

        // Enhanced search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('agent_services.bvn', 'like', "%$search%")
                  ->orWhere('agent_services.nin', 'like', "%$search%")
                  ->orWhere('agent_services.tracking_id', 'like', "%$search%")
                  ->orWhere('agent_services.reference', 'like', "%$search%")
                  ->orWhere('agent_services.performed_by', 'like', "%$search%")
                  ->orWhere('agent_services.user_id', 'like', "%$search%");
            });
        }

        if ($statusFilter) {
            $query->where('agent_services.status', $statusFilter);
        }

        // Apply custom status order + submission_date
        $enrollments = $query
            ->orderByRaw("CASE agent_services.status
                WHEN 'pending' THEN 1
                WHEN 'processing' THEN 2
                WHEN 'in-progress' THEN 3
                WHEN 'query' THEN 4
                WHEN 'resolved' THEN 5
                WHEN 'successful' THEN 6
                WHEN 'rejected' THEN 7
                WHEN 'failed' THEN 8
                WHEN 'remark' THEN 9
                ELSE 999 END")
            ->orderByDesc('agent_services.submission_date')
            ->paginate(10);

        // Status counts filtered by service_type (Fixed to match all variants)
        $statusCounts = [
            'pending'    => AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])->where('status', 'pending')->count(),
            'processing' => AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])->where('status', 'processing')->count(),
            'resolved'   => AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])->whereIn('status', ['resolved', 'successful'])->count(),
            'rejected'   => AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])->whereIn('status', ['rejected', 'failed'])->count(),
        ];

        return view('validation.index', compact('enrollments', 'search', 'statusFilter', 'statusCounts'));
    }

    /**
     * Show details of a single validation service
     */
    public function show($id)
    {
        $enrollmentInfo = AgentService::findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status' => $enrollmentInfo->status,
                'comment' => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at' => $enrollmentInfo->updated_at,
                'file_url' => $enrollmentInfo->file_url,
            ]
        ]);

        return view('validation.view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    /**
     * Update the status of a validation service
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,in-progress,resolved,successful,rejected,failed,query,remark',
            'comment' => 'nullable|string',
            'force_refund' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = AgentService::findOrFail($id);
            $oldStatus = $enrollment->status;
            $user = User::find($enrollment->user_id);

            $enrollment->status = $request->status;
            $enrollment->comment = $request->comment;
            $enrollment->save();

            // Handle refund logic if rejected
            if ($request->status === 'rejected') {
                if ($oldStatus !== 'rejected' || $request->force_refund) {
                    $this->processRefund($enrollment, $request->force_refund);
                }
            }

            // Send email notification to user
            if ($user && $user->email) {
                $this->sendStatusUpdateEmail($user, $enrollment);
            }

            DB::commit();
            return redirect()->route('validation.index')
                ->with('successMessage', 'Status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('validation.index')
                ->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund when a request is rejected
     */
    private function processRefund($enrollment, $forceRefund = false)
    {
        $serviceFieldId = $enrollment->service_field_id;
        $user = User::find($enrollment->user_id);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        if (!$serviceFieldId) {
            throw new \Exception('Service field ID is missing.');
        }

        $serviceField = ServiceField::find($serviceFieldId);

        if (!$serviceField) {
            throw new \Exception('Service field not found.');
        }

        $role = strtolower($user->role ?? 'default');

        // Check if refund already exists
        $refundExists = Transaction::where('type', 'refund')
            ->where('description', 'LIKE', "%Request ID #{$enrollment->id}%")
            ->exists();

        if ($refundExists && !$forceRefund) {
            throw new \Exception('Refund already processed for this request.');
        }

        // Fetch price for role, fallback to base price
        $servicePrice = DB::table('service_prices')
            ->where('service_fields_id', $serviceFieldId)
            ->where('user_type', $role)
            ->value('price');

        $basePrice = $servicePrice ?: $serviceField->base_price;

        if (!$basePrice || $basePrice <= 0) {
            throw new \Exception('No valid price found for refund.');
        }

        $refundAmount = round($basePrice * 0.8, 2);
        $debitAmount = round($basePrice * 0.2, 2);

        $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

        if (!$wallet) {
            throw new \Exception('Wallet not found for user.');
        }

        // Update wallet balance
        $wallet->balance += $refundAmount;
        $wallet->save();

        // Create refund transaction
        Transaction::create([
            'transaction_ref' => strtoupper(Str::random(12)),
            'user_id' => $user->id,
            'performed_by' => Auth::user()->first_name . ' ' . (Auth::user()->last_name ?? ''),
            'amount' => $refundAmount,
            'fee' => 0.00,
            'net_amount' => $refundAmount,
            'description' => "Refund 80% for rejected service [{$serviceField->field_name}], Request ID #{$enrollment->id}",
            'type' => 'refund',
            'status' => 'completed',
            'metadata' => json_encode([
                'service_id' => $enrollment->service_id,
                'service_field_id' => $serviceFieldId,
                'field_code' => $serviceField->field_code,
                'field_name' => $serviceField->field_name ?? null,
                'user_role' => $role,
                'base_price' => $basePrice,
                'percentage_refunded' => 80,
                'amount_debited_by_system' => $debitAmount,
                'forced_refund' => $forceRefund,
            ]),
        ]);
    }

    /**
     * Send custom email notification to user about status update
     */
    private function sendStatusUpdateEmail($user, $enrollment, $fileUrl = null)
    {
        $service = Service::find($enrollment->service_id);
        $serviceName = $service ? $service->name : 'Validation Service';
        
        $serviceField = ServiceField::find($enrollment->service_field_id);
        $fieldName = $serviceField ? $serviceField->field_name : 'Service';

        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'service_name' => $serviceName,
            'field_name' => $fieldName,
            'status' => ucfirst($enrollment->status),
            'comment' => $enrollment->comment,
            'file_url' => $fileUrl ? Storage::url($fileUrl) : null,
            'request_id' => $enrollment->id,
            'reference' => $enrollment->reference,
            'bvn' => $enrollment->bvn,
            'nin' => $enrollment->nin,
        ];

        Mail::send('emails.bvn-status-update', $data, function($message) use ($user, $serviceName) {
            $message->to($user->email)
                    ->subject("Status Update: {$serviceName} Request");
        });
    }

    /**
     * Check status for a specific NIN or ID
     */
    public function checkStatus(Request $request, $id = null)
    {
        $user = Auth::user();
        // Permission check: All authenticated users can check. 
        // We've removed the 'active' check to allow inactive or regular users to sync/check.

        try {
            if ($id) {
                $agentService = AgentService::findOrFail($id);
            } else {
                $request->validate([
                    'nin' => 'required|string',
                ]);
                $agentService = AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])
                    ->where(function($q) use ($request) {
                        $q->where('nin', $request->nin)->orWhere('tracking_id', $request->nin);
                    })
                    ->orderBy('created_at', 'desc')
                    ->firstOrFail();
            }

            $apiKey = env('NIN_API_KEY');
            
            // Always use NIN validation endpoint for this controller
            $url = 'https://s8v.ng/api/validation/status';
            $payload = [
                'nin' => $agentService->nin,
                'token' => $apiKey
            ];

            $response = Http::post($url, $payload);
            $apiResponse = $response->json();

            // Check if response failed but contains record not found error
            if (!$response->successful()) {
                $errorMsg = $apiResponse['error'] ?? $apiResponse['message'] ?? 'API Error';
                if (stripos($errorMsg, 'record not found') !== false) {
                    $agentService->update(['comment' => 'Record not found on S8V API']);
                    return back()->with('infoMessage', 'Record not found on S8V API.');
                }
                throw new \Exception($errorMsg);
            }

            // Clean the API response
            $cleanResponse = $this->cleanApiResponse($apiResponse);

            // Prepare update data
            $updateData = [
                'comment' => $cleanResponse,
            ];

            // Determine status from API response
            if (isset($apiResponse['status'])) {
                $updateData['status'] = $this->normalizeStatus($apiResponse['status']);
            } elseif (isset($apiResponse['response'])) {
                $updateData['status'] = $this->normalizeStatus($apiResponse['response']);
            }

            // Update the agent service record
            $agentService->update($updateData);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'nin' => $agentService->nin,
                    'tracking_id' => $agentService->tracking_id,
                    'status' => $agentService->status,
                    'response' => $apiResponse,
                    'clean_comment' => $cleanResponse
                ]);
            }

            return back()->with('successMessage', 'Status checked successfully. Current status: ' . $agentService->status);

        } catch (\Exception $e) {
            Log::error('Status Check Error: ' . $e->getMessage());

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to check status: ' . $e->getMessage(),
                    'status' => 'error'
                ], 500);
            }
            return back()->with('errorMessage', 'Unable to complete the request. Please try again.');
        }
    }

    /**
     * Bulk check and sync status for pending/processing NIN Validation requests
     */
    public function checkS8vBulkStatus(Request $request)
    {
        $user = Auth::user();
        // Permission check relaxed - even inactive users can sync pending/processing records.

        $apiKey = env('NIN_API_KEY');

        if (!$apiKey) {
            return redirect()->back()->with('errorMessage', 'API Key is missing in .env');
        }

        set_time_limit(300);

        $enrollments = AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])
            ->whereIn('status', ['pending', 'processing', 'in-progress'])
            ->limit(50)
            ->get();

        $remainingCount = AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])
            ->whereIn('status', ['pending', 'processing', 'in-progress'])
            ->count() - $enrollments->count();

        if ($enrollments->isEmpty()) {
            return redirect()->back()->with('infoMessage', 'No pending or processing NIN Validation requests to sync.');
        }

        $checkedCount = 0;
        $updatedCount = 0;
        $failedCount = 0;
        $noRecordCount = 0;

        foreach ($enrollments as $enrollment) {
            try {
                $nin = $enrollment->nin;
                
                if (!$nin) {
                    $fieldData = json_decode($enrollment->field, true);
                    $nin = $fieldData['nin'] ?? null;
                }

                if (!$nin) continue;

                $url = 'https://s8v.ng/api/validation/status';
                $payload = [
                    'nin' => $nin,
                    'token' => $apiKey
                ];

                $response = Http::post($url, $payload);
                $apiResponse = $response->json();
                
                if ($response->successful()) {
                    $checkedCount++;
                    $cleanResponse = $this->cleanApiResponse($apiResponse);
                    
                    $statusMessage = $apiResponse['message'] ?? $apiResponse['response'] ?? $cleanResponse;
                    
                    $responseStr = json_encode($apiResponse);
                    if (stripos($responseStr, 'no record') !== false) {
                        $noRecordCount++;
                    }

                    $updateData = ['comment' => $statusMessage];

                    if (isset($apiResponse['status'])) {
                        $updateData['status'] = $this->normalizeStatus($apiResponse['status']);
                    } elseif (isset($apiResponse['response'])) {
                        $updateData['status'] = $this->normalizeStatus($apiResponse['response']);
                    }

                    if (isset($updateData['status']) && $updateData['status'] !== $enrollment->status) {
                        $enrollment->update($updateData);
                        $updatedCount++;
                    } else {
                        $enrollment->update(['comment' => $statusMessage]);
                    }
                } else {
                    $errorMsg = $apiResponse['error'] ?? $apiResponse['message'] ?? 'API Error';
                    if (stripos($errorMsg, 'record not found') !== false) {
                        $enrollment->update(['comment' => 'Record not found on S8V API']);
                        $noRecordCount++;
                        $checkedCount++;
                    } else {
                        Log::warning('S8V NIN Validation API failed for NIN: ' . $nin, ['response' => $response->body()]);
                        $failedCount++;
                    }
                }
            } catch (\Exception $e) {
                Log::error('S8V NIN Bulk Status Check Error: ' . $e->getMessage(), ['id' => $enrollment->id]);
                $failedCount++;
            }
        }

        return redirect()->back()->with('statusSyncResult', [
            'provider' => 'S8V NIN',
            'updated' => $updatedCount,
            'failed' => $failedCount,
            'no_record' => $noRecordCount,
            'checked' => $checkedCount,
            'remaining' => max(0, $remainingCount)
        ]);
    }

    /**
     * Webhook receiver for S8v notifications
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('NIN Validation Webhook Received', $data);

        $identifier = $data['nin'] ?? null;

        if ($identifier) {
            $submission = AgentService::where('nin', $identifier)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($submission) {
                // Clean the webhook response
                $cleanResponse = $this->cleanApiResponse($data);
                
                $updateData = [
                    'comment' => $cleanResponse,
                ];

                if (isset($data['status'])) {
                    $updateData['status'] = $this->normalizeStatus($data['status']);
                }

                $submission->update($updateData);

                Log::info('NIN Validation Updated via Webhook', [
                    'submission_id' => $submission->id,
                    'identifier' => $identifier,
                    'new_status' => $updateData['status'] ?? 'unknown'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully'
        ]);
    }

    /**
     * Clean API response by removing unwanted characters
     */
    private function cleanApiResponse($response): string
    {
        if (is_array($response)) {
            // If it's an array, try to extract specific info or make it a neat string
            if (isset($response['message'])) return (string) $response['message'];
            if (isset($response['response'])) return (string) $response['response'];
            
            return collect($response)
                ->map(fn($v, $k) => is_array($v) ? "$k: " . json_encode($v) : "$k: $v")
                ->implode(' | ');
        }

        return trim((string) $response);
    }

    /**
     * Normalize status from various API response formats
     */
    private function normalizeStatus($status): string
    {
        $s = strtolower(trim((string) $status));
        
        return match ($s) {
            'successful', 'success', 'resolved', 'approved', 'in-progress', 'completed' => 'successful',
            'processing', 'pending', 'submitted', 'new' => 'processing',
            'failed', 'rejected', 'error', 'declined', 'invalid', 'no record' => 'failed',
            'query' => 'query',
            'remark' => 'remark',
            default => 'pending',
        };
    }
}