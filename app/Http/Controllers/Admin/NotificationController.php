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
    /**
     * Display a listing of notifications/announcements.
     */
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

    /**
     * Search users for single notification.
     */
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

    /**
     * Send email notifications to users.
     */
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

        // Handle image upload and generate URL
        $imageUrl = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('notifications', 'public');
            $imageUrl = $this->generateImageUrl($path);
        }

        $senderName = trim(Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name);

        // Log the announcement/notification
        $announcement = Announcement::create([
            'type' => 'email',
            'recipient_type' => $request->type,
            'recipient_data' => $this->getRecipientData($request),
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment' => $imageUrl, // Store as URL instead of path
            'is_active' => true,
            'status' => 'sent',
            'performed_by' => $senderName,
        ]);

        try {
            return $this->processNotification($request, $imageUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Store site-wide announcements.
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $senderName = trim(Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name);

        Announcement::create([
            'type' => 'announcement',
            'recipient_type' => 'none',
            'recipient_data' => null,
            'subject' => 'Site Announcement',
            'message' => $request->message,
            'is_active' => $request->boolean('is_active'),
            'status' => 'active',
            'performed_by' => $senderName,
        ]);

        return back()->with('status', 'Site announcement created successfully.');
    }

    /**
     * Store app adverts with images.
     */
    public function storeAdvert(Request $request)
    {
        $request->validate([
            'subject'      => 'required|string|max:255',
            'message'      => 'required|string',
            'service_name' => 'nullable|string|max:255',
            'discount'     => 'nullable|string|max:255',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        // Handle image upload and generate URL
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('adverts', 'public');
            $imageUrl = $this->generateImageUrl($path);
        }

        $senderName = trim(Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name);

        Announcement::create([
            'type'          => 'advert',
            'recipient_type'=> 'none',
            'recipient_data'=> null,
            'subject'       => $request->subject,
            'message'       => $request->message,
            'service_name'  => $request->service_name,
            'discount'      => $request->discount,
            'image'         => $imageUrl, // Store as URL instead of path
            'is_active'     => true,
            'status'        => 'active',
            'performed_by'  => $senderName,
        ]);

        return back()->with('status', 'App Advert posted successfully!');
    }

    /**
     * Toggle active status of announcements.
     */
    public function toggleStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();
        
        return back()->with('status', 'Announcement status updated.');
    }

    /**
     * Delete an announcement/notification.
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete image file if it exists (extract path from URL)
        if ($announcement->attachment || $announcement->image) {
            $imageField = $announcement->attachment ?? $announcement->image;
            $this->deleteImageFile($imageField);
        }

        $announcement->delete();
        return back()->with('status', 'Notification deleted successfully.');
    }

    /**
     * Generate full URL for uploaded image using env APP_URL.
     *
     * @param string $path
     * @return string|null
     */
    private function generateImageUrl($path)
    {
        if (!$path) {
            return null;
        }
        
        $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
        return $appUrl . Storage::url($path);
    }

    /**
     * Extract file path from URL and delete from storage.
     *
     * @param string|null $imageUrl
     * @return void
     */
    private function deleteImageFile($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        // Extract the path from URL
        $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
        $path = str_replace($appUrl . '/storage/', '', $imageUrl);
        
        // Try to delete from both possible locations
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        } elseif (Storage::disk('public')->exists('notifications/' . $path)) {
            Storage::disk('public')->delete('notifications/' . $path);
        } elseif (Storage::disk('public')->exists('adverts/' . $path)) {
            Storage::disk('public')->delete('adverts/' . $path);
        }
    }

    /**
     * Get recipient data based on notification type.
     *
     * @param Request $request
     * @return mixed
     */
    private function getRecipientData(Request $request)
    {
        switch ($request->type) {
            case 'single':
                return $request->user_id;
            case 'role':
                return $request->role;
            case 'manual_email':
                return $request->manual_email;
            default:
                return null;
        }
    }

    /**
     * Process notification based on type.
     *
     * @param Request $request
     * @param string|null $imageUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processNotification(Request $request, $imageUrl)
    {
        if ($request->type === 'single') {
            $user = User::findOrFail($request->user_id);
            Mail::to($user->email)->send(new SendNotification($request->subject, $request->message, $imageUrl));
            return back()->with('status', 'Notification sent successfully to ' . $user->email);

        } elseif ($request->type === 'manual_email') {
            Mail::to($request->manual_email)->send(new SendNotification($request->subject, $request->message, $imageUrl));
            return back()->with('status', 'Notification sent successfully to ' . $request->manual_email);

        } elseif ($request->type === 'role') {
            User::where('role', $request->role)
                ->chunk(100, function ($users) use ($request, $imageUrl) {
                    foreach ($users as $user) {
                        dispatch(new SendNotificationJob($user, $request->subject, $request->message, $imageUrl));
                    }
                });
            return back()->with('status', 'Notification sending initiated for all ' . ucfirst($request->role) . ' users (Queued).');

        } else {
            // All Users
            User::chunk(100, function ($users) use ($request, $imageUrl) {
                foreach ($users as $user) {
                    dispatch(new SendNotificationJob($user, $request->subject, $request->message, $imageUrl));
                }
            });
            return back()->with('status', 'Notification sending initiated for all users (Queued).');
        }
    }
}