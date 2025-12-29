<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Notification</title>
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
            background-color: #c2910aff;
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
        .details-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #eeeeee;
        }
        .details-item:last-child {
            border-bottom: none;
        }
        .details-label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background-color: #ec6c02ff;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        img.embedded-image {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(is_array($mail_data) && isset($mail_data['amount']))
                <h1>Payment Notification</h1>
            @else
                <h1>{{ config('app.name') }} Notification</h1>
            @endif
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            @if(is_array($mail_data) && isset($mail_data['amount']))
                <p>We have received a new transaction on your account. Below are the details:</p>
                
                <div class="details">
                    <div class="details-item">
                        <span class="details-label">Transaction Type</span>
                        <span>{{ $mail_data['type'] ?? 'N/A' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Amount</span>
                        <span>₦{{ isset($mail_data['amount']) ? number_format((float)$mail_data['amount'], 2) : '0.00' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Reference No</span>
                        <span>{{ $mail_data['ref'] ?? 'N/A' }}</span>
                    </div>
                    <div class="details-item">
                        <span class="details-label">Bank/Gateway</span>
                        <span>{{ $mail_data['bankName'] ?? 'N/A' }}</span>
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

            <p style="margin-top: 20px;">Thank you for using our service.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
