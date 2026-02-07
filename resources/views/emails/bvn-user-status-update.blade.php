<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service_name }} Status Update</title>
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
        .details-card {
            background-color: #f1f5f9;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #FF6F28;
        }
        .detail-row {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .detail-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-value {
            color: #1e293b;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-resolved, .status-successful { background-color: #d1fae5; color: #065f46; }
        .status-rejected, .status-failed { background-color: #fee2e2; color: #991b1b; }
        
        .comment-box {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }
        .btn-action {
            display: inline-block;
            background: #FF6F28;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
        }
        .support-section {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 30px;
            text-align: center;
        }
        .support-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            display: block;
        }
        .social-link-item {
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
                <h1>{{ $service_name }} Update</h1>
            </div>
            <div class="content">
                <p>Hello <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>
                <p>We have an update regarding your request for <strong>{{ $service_name }}</strong>. Details are provided below:</p>
                
                <div class="details-card">
                    <div class="detail-row">
                        <span class="detail-label">Service:</span>
                        <span class="detail-value">{{ $service_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Field:</span>
                        <span class="detail-value">{{ $field_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Request ID:</span>
                        <span class="detail-value">#{{ $request_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reference:</span>
                        <span class="detail-value">{{ $reference }}</span>
                    </div>
                    <div class="detail-row" style="margin-top: 15px;">
                        <span class="detail-label">Status:</span>
                        <span class="status-badge status-{{ strtolower($status) }}">{{ $status }}</span>
                    </div>
                </div>

                @if($comment)
                <div class="comment-box">
                    <p style="margin: 0 0 10px 0; color: #92400e; font-weight: 700; font-size: 14px;">AGENT REMARKS:</p>
                    <p style="margin: 0; color: #78350f; line-height: 1.5;">{{ $comment }}</p>
                </div>
                @endif

                @if($file_url)
                <div style="text-align: center;">
                    <a href="{{ url($file_url) }}" class="btn-action" target="_blank">📥 View Document</a>
                </div>
                @endif

                <div style="margin-top: 30px; padding: 20px; background-color: #f8fafc; border-radius: 8px; text-align: center;">
                    @if(strtolower($status) === 'resolved' || strtolower($status) === 'successful')
                        <span style="font-size: 24px;">✅</span>
                        <p style="margin: 10px 0 0 0; font-weight: 600; color: #065f46;">Great news! Your request has been fulfilled.</p>
                    @elseif(strtolower($status) === 'rejected' || strtolower($status) === 'failed')
                        <span style="font-size: 24px;">❌</span>
                        <p style="margin: 10px 0 0 0; font-weight: 600; color: #991b1b;">Unfortunately, the request was unsuccessful.</p>
                    @else
                        <span style="font-size: 24px;">⏳</span>
                        <p style="margin: 10px 0 0 0; font-weight: 600; color: #1e40af;">We are processing your update.</p>
                    @endif
                </div>
            </div>

            <div class="support-section">
                <span class="support-title">Need Help? Join Our Community</span>
                
                <div style="margin-bottom: 20px;">
                    <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link-item">💬 WhatsApp Community</a>
                    <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link-item">🔵 Official Facebook</a>
                </div>
                
                <div style="padding: 10px; background-color: #eff6ff; border-radius: 6px; display: inline-block;">
                    <span style="color: #1e40af; font-size: 13px; font-weight: 600;">Support Line: 08064333983</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
