<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentService;
use App\Models\User;

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
            ->where('service_type', 'validation');

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
        ]);

        $enrollment = AgentService::findOrFail($id);
        $enrollment->status = $request->status;
        $enrollment->comment = $request->comment;
        $enrollment->save();

        return redirect()->route('validation.index')
            ->with('successMessage', 'Status updated successfully.');
    }
}
