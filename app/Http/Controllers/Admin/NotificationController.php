<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNotification;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notification.index');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('email', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->select('id', 'first_name', 'last_name', 'email')
                    ->limit(20)
                    ->get();
        
        return response()->json($users);
    }

    public function send(Request $request)
    {
        $request->validate([
            'type' => 'required|in:all,single',
            'user_id' => 'required_if:type,single',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('attachment')) {
            $imagePath = $request->file('attachment')->store('notifications', 'public');
        }

        if ($request->type === 'single') {
            $user = User::findOrFail($request->user_id);
            
            // Send immediately to ensure delivery without queue worker
            try {
                Mail::to($user->email)->send(new SendNotification($request->subject, $request->message, $imagePath));
                return back()->with('status', 'Notification sent successfully to ' . $user->email);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }

        } else {
            // Chunking users to avoid memory issues
            // For 'all' users, we MUST use queue because sending thousands of emails synchronously will timeout
            User::chunk(100, function ($users) use ($request, $imagePath) {
                foreach ($users as $user) {
                     dispatch(new SendNotificationJob($user, $request->subject, $request->message, $imagePath));
                }
            });

            return back()->with('status', 'Notification sending initiated for all users (Queued). Ensure queue worker is running.');
        }
    }
}
