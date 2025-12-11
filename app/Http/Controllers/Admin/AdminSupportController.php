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

class AdminSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')
            ->orderByRaw("FIELD(status, 'open', 'customer_reply', 'answered', 'closed')")
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.support.index', compact('tickets'));
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
}
