<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BvnUser;
use App\Models\User;
use App\Models\ServiceField;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BvnUserController extends Controller
{
    /**
     * List all bvn_user records with filters and pagination
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $bank_nameFilter = $request->input('bank_name');

        // Base query - removed service_type filtering
        $query = BvnUser::query();

        // Enhanced search: BVN, NIN, transaction_ref, agent name
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

        if ($bank_nameFilter) {
            $query->where('bank_name', $bank_nameFilter);
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

        // Status counts for all BVN users
        $statusCounts = [
            'pending'    => BvnUser::where('status', 'pending')->count(),
            'processing' => BvnUser::where('status', 'processing')->count(),
            'resolved'   => BvnUser::whereIn('status', ['resolved', 'successful'])->count(),
            'rejected'   => BvnUser::whereIn('status', ['rejected', 'failed'])->count(),
        ];

        // Get distinct bank_names for filter
        $bank_names = $this->getDistinctbank_names();

        // Get authenticated user
        $user = Auth::user();

        return view('bvn.bvnuser', compact('enrollments', 'search', 'statusFilter', 'bank_nameFilter', 'statusCounts', 'user', 'bank_names'));
    }

    /**
     * Show details of a single bvn_user record
     */
    public function show($id)
    {
        $enrollmentInfo = BvnUser::findOrFail($id);
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

        return view('bvn.bvnuser-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    /**
     * Update the status of a bvn_user record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,in-progress,resolved,successful,rejected,failed,query,remark',
            'comment' => 'nullable|string',
            'agent_code' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
            'force_refund' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = BvnUser::findOrFail($id);
            $oldStatus = $enrollment->status;
            $user = User::find($enrollment->user_id);

            // Handle file upload
            $fileUrl = $enrollment->file_url;
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($fileUrl && Storage::disk('public')->exists($fileUrl)) {
                    Storage::disk('public')->delete($fileUrl);
                }

                // Store new file
                $file = $request->file('file');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('project-files/bvn-user', $fileName, 'public');
                $fileUrl = Storage::url($filePath);
            }

            // Update enrollment
            $enrollment->status = $request->status;
            $enrollment->comment = $request->comment;
            $enrollment->agent_code = $request->agent_code;
            $enrollment->file_url = $fileUrl;
            $enrollment->save();

            // Handle refund logic if rejected
            if ($request->status === 'rejected') {
                if ($oldStatus !== 'rejected' || $request->force_refund) {
                    $this->processRefund($enrollment, $request->force_refund);
                }
            }

            // Send email notification to user
            if ($user && $user->email) {
                $this->sendStatusUpdateEmail($user, $enrollment, $fileUrl);
            }

            DB::commit();
            return redirect()->route('bvnuser.index')
                ->with('successMessage', 'Status updated successfully and notification sent to user.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bvnuser.index')
                ->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund when an enrollment is rejected
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
        $serviceName = $service ? $service->name : 'BVN User Service';
        
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
        ];

        Mail::send('emails.bvn-user-status-update', $data, function($message) use ($user, $serviceName) {
            $message->to($user->email)
                    ->subject("Status Update: {$serviceName} Request");
        });
    }

    /**
     * Get distinct bank_names from bvn_users table
     */
    private function getDistinctbank_names()
    {
        return BvnUser::whereNotNull('bank_name')
            ->where('bank_name', '!=', '')
            ->distinct()
            ->pluck('bank_name')
            ->sort()
            ->values();
    }
}