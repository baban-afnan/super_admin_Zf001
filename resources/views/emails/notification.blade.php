<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            color: #555555;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }
        .details-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eeeeee;
        }
        .details-item:last-child {
            border-bottom: none;
        }
        .details-label {
            color: #888888;
            font-weight: 500;
            font-size: 14px;
        }
        .details-value {
            font-weight: 600;
            color: #333333;
            font-size: 15px;
            text-align: right;
        }
        .footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 30px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 13px;
        }
        .social-icons {
            margin-bottom: 20px;
        }
        .social-icons a {
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
        }
        .social-icons img {
            width: 24px;
            height: 24px;
            opacity: 0.6;
            transition: opacity 0.3s;
        }
        .social-icons a:hover img {
            opacity: 1;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #764ba2;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 10px;
        }
        img.embedded-image {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        @media only screen and (max-width: 600px) {
            .container { margin: 0; border-radius: 0; }
            .content { padding: 25px 20px; }
            .header { padding: 25px 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(is_array($mail_data) && isset($mail_data['amount']))
                <h1>Payment Notification</h1>
            @else
                <h1>{{ config('app.name') }}</h1>
            @endif
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            @if(is_array($mail_data) && isset($mail_data['amount']))
                <p>We have received a new transaction on your account. Below are the details:</p>
                
                <div class="details">
                    <div class="details-item">
                        <span class="details-label">Transaction Type</span>
                        <span class="details-value">{{ $mail_data['type'] ?? 'N/A' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Amount</span>
                        <span class="details-value">₦{{ isset($mail_data['amount']) ? number_format((float)$mail_data['amount'], 2) : '0.00' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Reference No</span>
                        <span class="details-value">{{ $mail_data['ref'] ?? 'N/A' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Bank/Gateway</span>
                        <span class="details-value">{{ $mail_data['bankName'] ?? 'N/A' }}</span>
                    </div>
                </div>
            @else
                {{-- Generic Content --}}
                <p>{!! nl2br(e($mail_data)) !!}</p>
            @endif

            @if(isset($imagePath) && $imagePath)
                <div style="text-align: center;">
                    <img src="{{ $message->embed(storage_path('app/public/' . $imagePath)) }}" class="embedded-image" alt="Attached Image">
                </div>
            @endif

            <p style="margin-top: 30px;">Thank you for using our service.</p>
        </div>
        
        <div class="footer">
            <div class="social-icons">
                 <!-- Using public CDN for icons since local assets might not be available in email clients -->
                 <a href="#" target="_blank" title="Facebook"><img src="https://img.icons8.com/ios-filled/50/764ba2/facebook-new.png" alt="Facebook"></a>
                 <a href="#" target="_blank" title="Twitter"><img src="https://img.icons8.com/ios-filled/50/764ba2/twitter.png" alt="Twitter"></a>
                 <a href="#" target="_blank" title="Instagram"><img src="https://img.icons8.com/ios-filled/50/764ba2/instagram-new.png" alt="Instagram"></a>
                 <a href="#" target="_blank" title="LinkedIn"><img src="https://img.icons8.com/ios-filled/50/764ba2/linkedin.png" alt="LinkedIn"></a>
            </div>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin-top: 5px;">This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
