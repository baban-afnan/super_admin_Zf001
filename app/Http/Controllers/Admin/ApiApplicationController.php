<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApiApplicationApproved;

class ApiApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiApplication::with(['user.wallet']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('api_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status != 'all' && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Get all applications, pending first, then by date desc
        $applications = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.api_applications.index', compact('applications'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $application = ApiApplication::findOrFail($id);
        $user = $application->user;

        if ($request->status === 'approved') {
            // Generate API token if not already exists (or regenerate)
            // Ideally we check if user already has one, but "create api_token" implies generating a new one
            // Let's generate a secure random string
            $token = Str::random(60);
            
            $user->api_token = $token;
            $user->save();

            // Notify the user via mail
            try {
                Mail::to($user->email)->send(new ApiApplicationApproved($user));
            } catch (\Exception $e) {
                // Log the error or handle it gracefully
                // \Log::error('Failed to send API approval email: ' . $e->getMessage());
            }
        }

        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}
