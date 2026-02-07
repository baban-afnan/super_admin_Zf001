<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Approved - {{ config('app.name') }}</title>
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
        .success-banner {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }
        .btn-action {
            display: inline-block;
            background: #FF6F28;
            color: #ffffff !important;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(255, 111, 40, 0.2);
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
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Application Approved!</h1>
            </div>
            
            <div class="content">
                <p>Hello <strong>{{ $user->first_name ?? 'User' }}</strong>,</p>
                
                <div class="success-banner">
                    <span style="font-size: 40px;">🎉</span>
                    <p style="margin: 15px 0 0 0; font-weight: 700; color: #166534; font-size: 18px;">Congratulations!</p>
                    <p style="margin: 5px 0 0 0; color: #15803d;">Your API application has been successfully reviewed and approved.</p>
                </div>
                
                <p>You now have full access to our powerful API infrastructure. We are excited to see what you build with <strong>Arewa Smart</strong>.</p>
                
                <p>Click the button below to access your developer portal and get started:</p>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://api.arewasmart.com.ng" class="btn-action">Go to Developer Portal</a>
                </div>
                
                <p style="font-size: 14px; color: #64748b; text-align: center;">
                    Direct link: <a href="https://api.arewasmart.com.ng" style="color: #FF6F28; text-decoration: none;">api.arewasmart.com.ng</a>
                </p>
            </div>

            <div class="support-section">
                <span class="support-title">Developer Support & Community</span>
                <p style="font-size: 14px; margin-bottom: 20px;">Join our developer community and get technical assistance.</p>
                
                <div style="margin-bottom: 20px;">
                    <a href="https://chat.whatsapp.com/KoSu12yDO4A8b6AvYSkvIx" class="social-link-item">💬 Join WhatsApp Group</a>
                    <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link-item">🔵 Follow Us on Facebook</a>
                </div>
                
                <div style="padding: 10px; background-color: #eff6ff; border-radius: 6px; display: inline-block;">
                    <span style="color: #1e40af; font-size: 13px; font-weight: 600;">Technical Hotline: 08064333983</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
                <p style="margin-top: 10px;">{{ config('app.url') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
