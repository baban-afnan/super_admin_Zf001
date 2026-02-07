<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Verification - {{ config('app.name') }}</title>
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
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
            overflow: hidden;
        }
        .header {
            background: #1e293b;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content p {
            color: #475569;
            line-height: 1.7;
            font-size: 15px;
            margin-bottom: 25px;
        }
        .otp-container {
            background-color: #f1f5f9;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 2px solid #e2e8f0;
        }
        .otp-code {
            color: #FF6F28;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 8px;
            font-family: 'Courier New', Courier, monospace;
            display: block;
        }
        .expiry-badge {
            display: inline-block;
            background-color: #fef2f2;
            color: #ef4444;
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 12px;
            margin-top: 15px;
        }
        .security-banner {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            text-align: left;
            border-left: 4px solid #1e293b;
        }
        .security-item {
            display: block;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Identity Verification</h1>
            </div>
            
            <div class="content">
                <p>Hello,</p>
                <p>You are receiving this code because a login or sensitive action was initiated on your <strong>Arewa Smart</strong> account.</p>
                
                <div class="otp-container">
                    <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; display: block;">Your Verification Code</span>
                    <span class="otp-code">{{ $code }}</span>
                    <span class="expiry-badge">Expires in 10 minutes</span>
                </div>

                <div class="security-banner">
                    <strong style="color: #1e293b; display: block; margin-bottom: 10px; font-size: 14px;">Secure Your Account:</strong>
                    <span class="security-item">• Never share this code with anyone, including Arewa Smart staff.</span>
                    <span class="security-item">• If you did not request this code, your account might be at risk. please secure your account immediately.</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
                <p style="margin-top: 10px; font-size: 11px;">This code was sent to protect your account security.</p>
            </div>
        </div>
    </div>
</body>
</html>
