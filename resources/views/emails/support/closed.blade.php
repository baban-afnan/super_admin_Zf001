<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Resolved - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding-bottom: 40px; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); margin-top: 30px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: #ffffff; padding: 35px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 40px 30px; }
        .content p { color: #475569; line-height: 1.7; font-size: 16px; margin-bottom: 20px; }
        .ticket-info { background-color: #f1f5f9; padding: 25px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #6366f1; }
        .info-row { margin-bottom: 8px; display: flex; justify-content: space-between; }
        .info-label { color: #64748b; font-size: 14px; font-weight: 600; }
        .info-value { color: #1e293b; font-weight: 700; }
        .status-badge { display: inline-block; background-color: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 50px; font-weight: 700; font-size: 13px; text-transform: uppercase; border: 1px solid #e2e8f0; }
        .history-section { margin-top: 40px; border-top: 2px solid #f1f5f9; padding-top: 30px; }
        .message-bubble { margin-bottom: 20px; padding: 15px; border-radius: 10px; }
        .admin-msg { background-color: #eff6ff; border-left: 4px solid #3b82f6; }
        .user-msg { background-color: #f8fafc; border-left: 4px solid #94a3b8; }
        .msg-meta { font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 4px; text-transform: uppercase; }
        .msg-text { color: #334155; font-size: 14px; }
        .footer { text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 13px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Conversation Resolved!</h1>
            </div>
            
            <div class="content">
                <p>Hello <strong>{{ $ticket->user->first_name }}</strong>,</p>
                <p>Your support conversation has been marked as resolved and closed by our team.</p>
                
                <div class="ticket-info">
                    <div class="info-row">
                        <span class="info-label">Reference ID:</span>
                        <span class="info-value">#{{ $ticket->reference }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge">Closed / Resolved</span>
                    </div>
                </div>

                <div class="history-section">
                    <span style="font-weight: 700; color: #1e293b; font-size: 18px; margin-bottom: 20px; display: block;">Chat History</span>
                    
                    @foreach($ticket->messages as $message)
                        <div class="message-bubble {{ $message->role == 'admin' ? 'admin-msg' : 'user-msg' }}">
                            <div class="msg-meta">{{ $message->role == 'admin' ? 'Support Team' : 'You' }} • {{ $message->created_at->format('M j, h:i A') }}</div>
                            <div class="msg-text">{{ $message->content }}</div>
                        </div>
                    @endforeach
                </div>

                <p style="margin-top: 30px;">If you have more questions, feel free to open a new conversation.</p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
