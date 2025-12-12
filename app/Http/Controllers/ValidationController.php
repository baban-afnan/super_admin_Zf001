<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentService;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceField;
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
            ->where('service_type', 'NIN_VALIDATION');

        // Enhanced search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('bvn', 'like', "%$search%")
                  ->orWhere('nin', 'like', "%$search%")
                  ->orWhere('reference', 'like', "%$search%")
                  ->orWhere('performed_by', 'like', "%$search%")
                  ->orWhere('user_id', 'like', "%$search%");
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Apply custom status order + submission_date
        $enrollments = $query
            ->orderByRaw("CASE status
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
            ->orderByDesc('submission_date')
            ->paginate(10);

        // Status counts filtered by service_type
        $statusCounts = [
            'pending'    => AgentService::where('service_type', 'validation')->where('status', 'pending')->count(),
            'processing' => AgentService::where('service_type', 'validation')->where('status', 'processing')->count(),
            'resolved'   => AgentService::where('service_type', 'validation')->whereIn('status', ['resolved', 'successful'])->count(),
            'rejected'   => AgentService::where('service_type', 'validation')->whereIn('status', ['rejected', 'failed'])->count(),
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
            'user_name' => $user->first_name . ' ' . $user->last_name,
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
}
