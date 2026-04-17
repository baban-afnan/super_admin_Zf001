<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
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
        // Subquery to get the latest message for EACH conversation group
        // A conversation group is defined by its 'reference' if present, 
        // OR by ('user_id' and 'type' if reference is null).
        $latestMessagesSub = AiChat::select(
                'user_id', 
                'type', 
                'reference', 
                \DB::raw('MAX(id) as latest_id')
            )
            ->whereIn('type', ['support', 'global'])
            ->groupBy('user_id', 'type', 'reference');

        $query = AiChat::joinSub($latestMessagesSub, 'latest_msgs', function ($join) {
                $join->on('ai_chats.id', '=', 'latest_msgs.latest_id');
            })
            ->with('user');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ai_chats.reference', 'like', "%{$search}%")
                  ->orWhere('ai_chats.subject', 'like', "%{$search}%")
                  ->orWhere('ai_chats.content', 'like', "%{$search}%") // Added content search
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            $query->where('ai_chats.status', $request->status);
        }

        $tickets = $query->orderByRaw("FIELD(ai_chats.status, 'open', 'customer_reply', 'answered', 'closed') DESC")
            ->latest('ai_chats.updated_at')
            ->paginate(15);

        // Statistics (Support + Global AI)
        $totalTickets = $this->countTotalConversations();
        
        // Monthly Received
        $monthlyReceived = $this->countTotalConversations(now()->month, now()->year);
            
        // Monthly Open (Currently open/customer_reply)
        $monthlyOpen = $this->countTotalConversations(now()->month, now()->year, ['open', 'customer_reply']);

        // Monthly Closed
        $monthlyClosed = $this->countTotalConversations(now()->month, now()->year, ['closed']);

        // Customer Reply
        $customer_reply = $this->countTotalConversations(now()->month, now()->year, ['customer_reply'], 'updated_at');

        // Chart Data: Status Distribution
        $latestStatusSub = AiChat::select('user_id', 'type', 'reference', \DB::raw('MAX(id) as latest_id'))
            ->whereIn('type', ['support', 'global'])
            ->groupBy('user_id', 'type', 'reference');
            
        $statusStats = AiChat::joinSub($latestStatusSub, 'ls', function($join) {
                $join->on('ai_chats.id', '=', 'ls.latest_id');
            })
            ->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Chart Data: Support Usage by User Role
        $roleStats = AiChat::join('users', 'ai_chats.user_id', '=', 'users.id')
            ->where('ai_chats.role', 'user')
            ->whereIn('ai_chats.type', ['support', 'global'])
            ->select('users.role', \DB::raw('count(distinct COALESCE(ai_chats.reference, CONCAT("global-", ai_chats.user_id))) as count'))
            ->groupBy('users.role')
            ->get();

        return view('admin.support.index', compact(
            'tickets', 'totalTickets', 'monthlyReceived', 'monthlyOpen', 'monthlyClosed', 'customer_reply',
            'statusStats', 'roleStats'
        ));
    }

    protected function countTotalConversations($month = null, $year = null, $statuses = [], $dateField = 'created_at')
    {
        $query = AiChat::whereIn('type', ['support', 'global']);

        if ($month) {
            $query->whereMonth($dateField, $month);
        }
        if ($year) {
            $query->whereYear($dateField, $year);
        }
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        return $query->select('reference', 'user_id', 'type')
            ->distinct()
            ->get()
            ->count();
    }

    public function fetchMessages($reference)
    {
        $messages = $this->getConversationMessages($reference);

        return view('admin.support.partials.messages', compact('messages', 'reference'))->render();
    }

    public function show($reference)
    {
        $messages = $this->getConversationMessages($reference);

        if ($messages->isEmpty()) {
            abort(404, 'Conversation not found.');
        }

        $ticket = $messages->first(); 
        $ticket->messages = $messages; 

        return view('admin.support.show', compact('ticket', 'messages', 'reference'));
    }

    protected function getConversationMessages($reference)
    {
        $query = AiChat::with('user')->orderBy('created_at', 'asc');

        if (str_starts_with($reference, 'AI-USER-')) {
            $userId = (int) str_replace('AI-USER-', '', $reference);
            $query->where('user_id', $userId)->where('type', 'global')->whereNull('reference');
        } else {
            $query->where('reference', $reference);
        }

        return $query->get();
    }

    public function reply(Request $request, $reference)
    {
        $messages = $this->getConversationMessages($reference);
        $lastMessage = $messages->last();

        if (!$lastMessage) {
            abort(404);
        }

        if ($lastMessage->status == 'closed') {
            return back()->with('error', 'Cannot reply to a closed conversation.');
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support-attachments', 'public');
        }

        AiChat::create([
            'user_id' => Auth::id(), 
            'reference' => $lastMessage->reference, // Might be null for global
            'type' => $lastMessage->type,
            'subject' => $lastMessage->subject,
            'status' => 'answered',
            'role' => 'admin',
            'content' => $request->message,
            'attachment' => $attachmentPath,
        ]);

        // Update status for the whole conversation group
        if ($lastMessage->reference) {
            AiChat::where('reference', $lastMessage->reference)->update(['status' => 'answered', 'updated_at' => now()]);
        } else {
            AiChat::where('user_id', $lastMessage->user_id)->where('type', 'global')->whereNull('reference')
                  ->update(['status' => 'answered', 'updated_at' => now()]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close($reference)
    {
        $messages = $this->getConversationMessages($reference);

        if ($messages->isEmpty()) {
            abort(404);
        }

        $lastMessage = $messages->last();
        if ($lastMessage->status == 'closed') {
            return back()->with('info', 'Conversation is already closed.');
        }

        if ($lastMessage->reference) {
            AiChat::where('reference', $lastMessage->reference)->update(['status' => 'closed', 'updated_at' => now()]);
        } else {
            AiChat::where('user_id', $lastMessage->user_id)->where('type', 'global')->whereNull('reference')
                  ->update(['status' => 'closed', 'updated_at' => now()]);
        }

        // Send email notification for formal support tickets
        if ($lastMessage->type == 'support') {
            $ticket = $messages->first();
            $ticket->messages = $messages;

            if ($ticket->user && $ticket->user->email) {
                try {
                    Mail::to($ticket->user->email)->send(new TicketClosed($ticket));
                } catch (\Exception $e) {
                    return back()->with('success', 'Conversation closed successfully, but failed to send email notification.');
                }
            }
        }

        return back()->with('success', 'Conversation status updated successfully.');
    }
    public function updates(Request $request, $reference)
    {
        $query = AiChat::where('id', '>', $request->last_message_id ?? 0);
        
        if (str_starts_with($reference, 'AI-USER-')) {
            $userId = (int) str_replace('AI-USER-', '', $reference);
            $query->where('user_id', $userId)->where('type', 'global')->whereNull('reference');
        } else {
            $query->where('reference', $reference);
        }

        return response()->json([
            'messages' => $query->get()
        ]);
    }
}
