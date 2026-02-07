<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket Resolved - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 40px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            color: #ffffff;
            padding: 35px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            color: #475569;
            line-height: 1.7;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .ticket-info {
            background-color: #f1f5f9;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #FF6F28;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }
        .info-value {
            color: #1e293b;
            font-weight: 700;
        }
        .status-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #475569;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            border: 1px solid #e2e8f0;
        }
        .history-section {
            margin-top: 40px;
            border-top: 2px solid #f1f5f9;
            padding-top: 30px;
        }
        .history-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 20px;
            display: block;
        }
        .message-bubble {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }
        .admin-msg {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
        }
        .user-msg {
            background-color: #f8fafc;
            border-left: 4px solid #94a3b8;
        }
        .msg-meta {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .msg-text {
            color: #334155;
            line-height: 1.6;
            font-size: 14px;
        }
        .support-footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 30px;
            text-align: center;
        }
        .footer-link {
            display: inline-block;
            margin: 0 10px;
            color: #FF6F28;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Ticket Resolved!</h1>
            </div>
            
            <div class="content">
                <p>Hello <strong>{{ $ticket->user->first_name }}</strong>,</p>
                <p>We're happy to inform you that your support ticket has been marked as resolved and closed by our team.</p>
                
                <div class="ticket-info">
                    <div class="info-row">
                        <span class="info-label">Reference ID:</span>
                        <span class="info-value">#{{ $ticket->ticket_reference }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge">Closed / Resolved</span>
                    </div>
                </div>

                <p>We hope we've met your expectations. If you need any further help, feel free to open a new ticket or join our community group.</p>

                <div class="history-section">
                    <span class="history-title">Conversation Highlights</span>
                    
                    @foreach($ticket->messages->take(3) as $message)
                        <div class="message-bubble {{ $message->is_admin_reply ? 'admin-msg' : 'user-msg' }}">
                            <div class="msg-meta">
                                {{ $message->is_admin_reply ? 'Arewa Support' : 'You' }} 
                                • {{ $message->created_at->format('M d, Y') }}
                            </div>
                            <div class="msg-text">
                                {{ $message->message }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="support-footer">
                <span style="font-weight: 700; color: #1e293b; display: block; margin-bottom: 15px;">Need More Help?</span>
                
                <div style="margin-bottom: 20px;">
                    <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="footer-link">💬 Join WhatsApp Group</a>
                    <a href="https://www.facebook.com/share/184dBwK8HX/" class="footer-link">🔵 Official Facebook</a>
                </div>
                
                <div style="padding: 10px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block;">
                    <span style="color: #1e40af; font-size: 13px; font-weight: 700;">Support Desk: 08064333983</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
