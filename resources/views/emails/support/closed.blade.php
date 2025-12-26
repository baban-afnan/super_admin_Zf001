<!DOCTYPE html>
<html>
<head>
    <title>Ticket Closed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
       
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            color: #555555;
            line-height: 1.6;
        }
        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .details p {
            margin: 5px 0;
            font-weight: bold;
        }
        .message-history {
            margin-top: 30px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .admin-msg {
            background-color: #f1f3f5;
            border-left: 4px solid #0d6efd;
        }
        .user-msg {
            background-color: #e7f5ff;
            border-left: 4px solid #198754;
        }
        .meta {
            font-size: 0.8em;
            color: #888;
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 14px;
            background-color: #e9ecef;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket Closed</h1>
        </div>
        <div class="content">
            <p>Hello {{ $ticket->user->first_name . ' ' . $ticket->user->last_name }},</p>
            
            <p>Your support ticket has been closed by our admin team. Below are the details:</p>

            <div class="details">
                <p>Reference: #{{ $ticket->ticket_reference }}</p>
                <p>Status: <span class="status-badge">Closed</span></p>
            </div>
            
            <p>If you have any further questions, please feel free to open a new ticket or reply to this email.</p>

            <div class="message-history">
                <h3 style="color: #333;">Conversation History</h3>
                
                @foreach($ticket->messages as $message)
                    <div class="message {{ $message->is_admin_reply ? 'admin-msg' : 'user-msg' }}">
                        <div class="meta">
                            {{ $message->is_admin_reply ? 'Support Agent' : 'You' }} 
                            - {{ $message->created_at->format('M d, Y h:i A') }}
                        </div>
                        <div style="color: #555;">
                            {{ $message->message }}
                        </div>
                        @if($message->attachment)
                            <div style="margin-top:5px; font-size:0.9em; color:#0d6efd;">
                                <i>[Attachment included]</i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
