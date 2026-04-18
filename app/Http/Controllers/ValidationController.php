<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AgentService;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceField;
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
            $user = User::find($enrollment->user_id);

            $enrollment->status = $request->status;
            $enrollment->comment = $request->comment;
            $enrollment->save();

            // Send email notification to user
            $user = User::find($enrollment->user_id);
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

            // Update status to processing to indicate it's in queue
            if (!in_array($agentService->status, ['successful', 'failed', 'rejected'])) {
                $agentService->update([
                    'status' => 'processing',
                    'comment' => 'Request is in queue for status check.'
                ]);
            }

            // Dispatch the background job
            \App\Jobs\CheckNINStatusJob::dispatch($agentService);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your request is in queue. We will notify you once completed.',
                    'nin' => $agentService->nin,
                    'status' => $agentService->status,
                ]);
            }

            return back()->with('successMessage', 'Your request is in queue. We will notify you once completed.');

        } catch (\Exception $e) {
            Log::error('Status Check Dispatch Error: ' . $e->getMessage());

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to queue status check: ' . $e->getMessage(),
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

        $apiKey = env('IDENFY_API_KEY');
        if (!$apiKey) {
            return redirect()->back()->with('errorMessage', 'API Key is missing in .env');
        }

        $enrollments = AgentService::whereIn('service_type', ['NIN_VALIDATION', 'validation', 'nin_validation'])
            ->whereIn('status', ['pending', 'processing', 'in-progress'])
            ->limit(100)
            ->get();

        if ($enrollments->isEmpty()) {
            return redirect()->back()->with('infoMessage', 'No pending or processing NIN Validation requests to sync.');
        }

        // Bulk update status to processing to indicate it's in queue
        AgentService::whereIn('id', $enrollments->pluck('id'))
            ->update([
                'status' => 'processing',
                'comment' => 'Request is in queue for bulk status check.'
            ]);

        foreach ($enrollments as $enrollment) {
            // Dispatch background job
            \App\Jobs\CheckNINStatusJob::dispatch($enrollment);
        }

        return redirect()->back()->with('successMessage', 'Bulk sync started. ' . $enrollments->count() . ' requests have been queued for processing.');
    }


}