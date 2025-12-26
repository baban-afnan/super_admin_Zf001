<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $recentAnnouncements = $query->paginate(20);
        return view('notification.index', compact('recentAnnouncements'));
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
            'type' => 'required|in:all,single,role,manual_email',
            'user_id' => 'nullable|required_if:type,single',
            'role' => 'nullable|required_if:type,role',
            'manual_email' => 'nullable|required_if:type,manual_email|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('attachment')) {
            $imagePath = $request->file('attachment')->store('notifications', 'public');
        }

        $senderName = Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name;
        $recipientData = null;

        // Log the announcement/notification
        $announcement = Announcement::create([
            'type' => 'email', // Default to email, can be announcement if added later
            'recipient_type' => $request->type,
            'recipient_data' => $request->type === 'single' ? $request->user_id : ($request->type === 'role' ? $request->role : ($request->type === 'manual_email' ? $request->manual_email : null)),
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment' => $imagePath,
            'is_active' => true,
            'status' => 'sent',
            'performed_by' => trim($senderName),
        ]);

        try {
            if ($request->type === 'single') {
                $user = User::findOrFail($request->user_id);
                Mail::to($user->email)->send(new SendNotification($request->subject, $request->message, $imagePath));
                return back()->with('status', 'Notification sent successfully to ' . $user->email);

            } elseif ($request->type === 'manual_email') {
                Mail::to($request->manual_email)->send(new SendNotification($request->subject, $request->message, $imagePath));
                return back()->with('status', 'Notification sent successfully to ' . $request->manual_email);

            } elseif ($request->type === 'role') {
                $query = User::where('role', $request->role);
                $query->chunk(100, function ($users) use ($request, $imagePath) {
                    foreach ($users as $user) {
                        dispatch(new SendNotificationJob($user, $request->subject, $request->message, $imagePath));
                    }
                });
                return back()->with('status', 'Notification sending initiated for all ' . ucfirst($request->role) . ' users (Queued).');

            } else {
                // All Users
                User::chunk(100, function ($users) use ($request, $imagePath) {
                    foreach ($users as $user) {
                         dispatch(new SendNotificationJob($user, $request->subject, $request->message, $imagePath));
                    }
                });
                return back()->with('status', 'Notification sending initiated for all users (Queued).');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
    
    // Method to store site-wide announcements
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'is_active' => 'nullable|boolean', // checkbox sends '1' or nothing
        ]);

        $senderName = Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name;

        Announcement::create([
            'type' => 'announcement',
            'recipient_type' => 'none', // Site broadcast doesn't target specific email lists
            'recipient_data' => null,
            'subject' => 'Site Announcement',
            'message' => $request->message,
            'is_active' => $request->boolean('is_active'),
            'status' => 'active',
            'performed_by' => trim($senderName),
        ]);

        return back()->with('status', 'Site announcement created successfully.');
    }

    // Method to toggle active status of site announcements if needed in future
    public function toggleStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();
        return back()->with('status', 'Announcement status updated.');
    }
}
