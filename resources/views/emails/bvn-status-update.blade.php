<!DOCTYPE html>
<html>
<head>
    <title>{{ $service_name }} Status Update</title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            padding: 20px 0;
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
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-resolved, .status-successful { background-color: #d1fae5; color: #065f46; }
        .status-rejected, .status-failed { background-color: #fee2e2; color: #991b1b; }
        .status-query { background-color: #fef3c7; color: #92400e; }
        .status-remark { background-color: #e0e7ff; color: #3730a3; }
        .status-in-progress { background-color: #dbeafe; color: #1e40af; }
        .comment-box {
            background-color: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .file-download {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $service_name }} Status Update</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user_name }},</p>
            <p>We have an update regarding your <strong>{{ $service_name }}</strong> request. Below are the details:</p>
            
            <div class="details">
                <p>Service: {{ $service_name }}</p>
                <p>Field: {{ $field_name }}</p>
                <p>Field: {{ $bvn }}</p>
                <p>Request ID: #{{ $request_id }}</p>
                <p>Reference: {{ $reference }}</p>
                <p>Status: <span class="status-badge status-{{ strtolower($status) }}">{{ $status }}</span></p>
            </div>

            @if($comment)
            <div class="comment-box">
                <p style="margin: 0; color: #78350f;"><strong>Staff Comment:</strong></p>
                <p style="margin: 10px 0 0 0; color: #78350f;">{{ $comment }}</p>
            </div>
            @endif

            @if($file_url)
            <p style="margin-top: 20px;">A document has been attached to your request:</p>
            <a href="{{ url($file_url) }}" class="file-download" target="_blank">📥 Download Document</a>
            @endif

            <p style="margin-top: 20px;">
                @if(strtolower($status) === 'resolved' || strtolower($status) === 'successful')
                    ✅ <strong>Congratulations!</strong> Your request has been successfully processed.
                @elseif(strtolower($status) === 'rejected' || strtolower($status) === 'failed')
                    ❌ Your request has been rejected. Please review the comment above. If applicable, a refund has been processed to your wallet.
                @elseif(strtolower($status) === 'processing')
                    ⏳ Your request is being processed. We'll notify you once completed.
                @elseif(strtolower($status) === 'query')
                    ❓ We need additional information. Please review the comment above.
                @else
                    📌 Your request status has been updated.
                @endif
            </p>

            <p>Thank you for using our service.</p>
            <p>For any further assistance, please contact our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
