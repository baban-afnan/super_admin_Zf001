<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; border-bottom: 2px solid #e9ecef; }
        .status { color: #6c757d; font-weight: bold; }
        .message-history { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 5px; }
        .admin-msg { background-color: #f1f3f5; border-left: 4px solid #0d6efd; }
        .user-msg { background-color: #e7f5ff; border-left: 4px solid #198754; }
        .meta { font-size: 0.8em; color: #888; margin-bottom: 5px; }
        .footer { margin-top: 30px; font-size: 0.8em; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Ticket Closed</h2>
            <p>Reference: <strong>#{{ $ticket->ticket_reference }}</strong></p>
            <p>Status: <span class="status">Closed</span></p>
        </div>

        <p>Hello {{ $ticket->user->first_name }},</p>
        
        <p>Your support ticket has been closed by our admin team. If you have any further questions, please feel free to open a new ticket or reply to this email.</p>

        <div class="message-history">
            <h3>Conversation History</h3>
            
            @foreach($ticket->messages as $message)
                <div class="message {{ $message->is_admin_reply ? 'admin-msg' : 'user-msg' }}">
                    <div class="meta">
                        {{ $message->is_admin_reply ? 'Support Agent' : 'You' }} 
                        - {{ $message->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div>
                        {{ $message->message }}
                    </div>
                    @if($message->attachment)
                        <div style="margin-top:5px; font-size:0.9em;">
                            <i>[Attachment included]</i>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
