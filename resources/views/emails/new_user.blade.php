<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Arewa Smart</title>
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
        .credentials {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .credentials p {
            margin: 5px 0;
            font-weight: bold;
        }
        .credential-value {
            font-family: monospace;
            color: #0d6efd;
            font-size: 16px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
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
            <h1>Welcome to Arewa Smart</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->first_name . ' ' . $user->last_name }},</p>
            <p>Your account has been successfully created. We are excited to have you on board! Below are your login credentials:</p>
            
            <div class="credentials">
                <p>Email: <span class="credential-value">{{ $user->email }}</span></p>
                <p>Password: <span class="credential-value">{{ $password }}</span></p>
            </div>

            <div class="warning-box">
                <p><strong>⚠️ Important:</strong> For security reasons, please change your password immediately after your first login.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
            </div>

            <p style="margin-top: 20px;">Thank you for joining us!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
            <p>If you did not request this account, please contact support immediately.</p>
        </div>
    </div>
</body>
</html>

