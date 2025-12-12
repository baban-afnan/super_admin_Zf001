<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketClosed;

use Illuminate\Support\Facades\Cache;

class AdminSupportController extends Controller
{
    public function typing($reference)
    {
        // Store typing status in cache for 10 seconds
        Cache::put('support_typing_' . $reference . '_admin', true, now()->addSeconds(10));
        return response()->json(['status' => 'ok']);
    }

    public function index(Request $request)
    {
        $query = SupportTicket::with('user');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_reference', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderByRaw("FIELD(status, 'open', 'customer_reply', 'answered', 'closed')")
            ->latest('updated_at')
            ->paginate(10);

        // Statistics
        $totalTickets = SupportTicket::count();
        
        // Monthly Received (All tickets created this month)
        $monthlyReceived = SupportTicket::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Monthly Open (Created this month and still open)
        $monthlyOpen = SupportTicket::where('status', 'open')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Monthly Closed (Created this month and closed)
        $monthlyClosed = SupportTicket::where('status', 'closed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Customer Reply (Updated this month with status customer_reply)
        $customer_reply = SupportTicket::where('status', 'customer_reply')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        return view('admin.support.index', compact('tickets', 'totalTickets', 'monthlyReceived', 'monthlyOpen', 'monthlyClosed', 'customer_reply'));
    }

    public function fetchMessages($reference)
    {
        $ticket = SupportTicket::where('ticket_reference', $reference)
            ->with(['messages.user', 'user'])
            ->firstOrFail();

        return view('admin.support.partials.messages', compact('ticket'))->render();
    }

    public function show($reference)
    {
        $ticket = SupportTicket::where('ticket_reference', $reference)
            ->with(['messages.user', 'user'])
            ->firstOrFail();

        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, $reference)
    {
        $ticket = SupportTicket::where('ticket_reference', $reference)->firstOrFail();

        if ($ticket->status == 'closed') {
            return back()->with('error', 'Cannot reply to a closed ticket.');
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support-attachments', 'public');
        }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(), // Admin user ID
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_admin_reply' => true,
        ]);

        $ticket->update(['status' => 'answered', 'updated_at' => now()]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close($reference)
    {
        $ticket = SupportTicket::where('ticket_reference', $reference)
            ->with(['messages', 'user'])
            ->firstOrFail();

        if ($ticket->status == 'closed') {
            return back()->with('info', 'Ticket is already closed.');
        }

        $ticket->update(['status' => 'closed', 'updated_at' => now()]);

        // Send email with chat history
        if ($ticket->user && $ticket->user->email) {
            try {
                Mail::to($ticket->user->email)->send(new TicketClosed($ticket));
            } catch (\Exception $e) {
                // Log error but don't fail the request
                // \Log::error('Failed to send ticket closed email: ' . $e->getMessage());
                return back()->with('success', 'Ticket closed successfully, but failed to send email notification.');
            }
        }

        return back()->with('success', 'Ticket closed and notification sent to user.');
    }
    public function updates(Request $request, $reference)
    {
        $ticket = SupportTicket::where('ticket_reference', $reference)->firstOrFail();
        
        $messages = [];
        if ($request->has('last_message_id')) {
            $messages = $ticket->messages()
                ->where('id', '>', $request->last_message_id)
                ->get();
        }

        return response()->json([
            'messages' => $messages
        ]);
    }
}
