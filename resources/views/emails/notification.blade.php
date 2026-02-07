<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Notification</title>
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
            font-size: 22px;
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
        .notification-card {
            background-color: #f1f5f9;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #FF6F28;
        }
        .detail-row {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .detail-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }
        .detail-value {
            color: #1e293b;
            font-weight: 700;
            font-size: 14px;
        }
        .embedded-img-container {
            margin-top: 30px;
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .embedded-img-container img {
            max-width: 100%;
            height: auto;
            display: block;
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
        .social-link {
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
        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                margin-top: 0 !important;
                border-radius: 0 !important;
            }
            .content {
                padding: 25px 20px !important;
            }
            .header {
                padding: 25px 20px !important;
            }
            .detail-row {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            .detail-label {
                margin-bottom: 4px;
            }
            .detail-value {
                font-size: 13px !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                @if(is_array($mail_data) && isset($mail_data['amount']))
                    <h1>Payment Update</h1>
                @else
                    <h1>System Notification</h1>
                @endif
            </div>
            
            <div class="content">
                <p>Hello,</p>
                
                @if(is_array($mail_data) && isset($mail_data['amount']))
                    <p>We've processed a new transaction on your account. Here are the latest details:</p>
                    
                    <div class="notification-card">
                        <div class="detail-row">
                            <span class="detail-label">Service Type</span>
                            <span class="detail-value">{{ $mail_data['type'] ?? 'Transaction' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Amount Paid</span>
                            <span class="detail-value" style="color: #059669;">₦{{ isset($mail_data['amount']) ? number_format((float)$mail_data['amount'], 2) : '0.00' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Reference ID</span>
                            <span class="detail-value">{{ $mail_data['ref'] ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Channel/Bank</span>
                            <span class="detail-value">{{ $mail_data['bankName'] ?? 'Internal' }}</span>
                        </div>
                    </div>
                @else
                    <div class="notification-card" style="background-color: #fffbeb; border-color: #fbbf24;">
                        <p style="margin: 0; color: #92400e; line-height: 1.6;">{!! nl2br(e($mail_data)) !!}</p>
                    </div>
                @endif

                @if(isset($imagePath) && $imagePath)
                    <div class="embedded-img-container">
                        <img src="{{ $message->embed(storage_path('app/public/' . $imagePath)) }}" alt="Notification Details">
                    </div>
                @endif

                <p style="margin-top: 30px;">Log in to your dashboard to view your full transaction history or update your settings.</p>
            </div>

            <div class="support-section">
                <span class="support-title">Need Help? Contact Us</span>
                
                <div style="margin-bottom: 20px;">
                    <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link">💬 WhatsApp Group</a>
                    <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link">🔵 Official Facebook</a>
                </div>
                
                <div style="padding: 10px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block;">
                    <span style="color: #1e40af; font-size: 13px; font-weight: 700;">Support Desk: 08064333983</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p style="margin-top: 8px;">This is an automated system email. Please do not reply directly.</p>
            </div>
        </div>
    </div>
</body>
</html>
