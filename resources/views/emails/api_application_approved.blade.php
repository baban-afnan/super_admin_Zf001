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
            padding: 20px;
            font-size: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content p {
            color: #555555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 12px;
            background-color: #f9f9f9;
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
            text-align: center;
        }
        .contact-info {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #c2910aff;
        }
        .social-links {
            margin-top: 20px;
            text-align: center;
        }
        .social-links a {
            color: #3b5998;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Approved!</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $user->first_name ?? 'User' }},</p>
            
            <p>Congratulations! Your API application has been successfully approved.</p>
            
            <p>You can now visit our API website to get started:</p>
            
            <div style="text-align: center;">
                <a href="https://api.arewasmart.com.ng" class="btn">Get Started</a>
            </div>
            <br>
            <p>Or visit directly: <a href="https://api.arewasmart.com.ng">api.arewasmart.com.ng</a></p>

            <div class="contact-info">
                <p><strong>Need Support?</strong></p>
                <p>Contact us on WhatsApp at: <strong>07037343660</strong></p>
            </div>

            <div class="social-links">
                <p>Follow us on Facebook:</p>
                <a href="https://www.facebook.com/share/184dBwK8HX/">Visit our Facebook Page</a>
            </div>

            <p style="margin-top: 30px;">Thank you for choosing {{ config('app.name') }}.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
