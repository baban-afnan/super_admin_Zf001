<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style type="text/css">
        /* Client-specific Styles */
        #outlook a {padding:0;}
        body {
            width:100% !important; 
            -webkit-text-size-adjust:100%; 
            -ms-text-size-adjust:100%; 
            margin:0; 
            padding:0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f3f6fb;
            color: #4a5568;
        }
        .ExternalClass {width:100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        
        /* Core Reset */
        table td {border-collapse: collapse;}
        img {outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; max-width: 100%; height: auto; border: 0;}
        a {text-decoration: none;}
        
        /* Layout */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f3f6fb;
            padding: 40px 0;
        }
        
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #edf2f7;
        }

        /* Content Areas */
        .content {
            padding: 40px;
            text-align: left;
        }
        
        /* Typography */
        h1, h2, h3, h4 {
            color: #1a202c;
            margin-top: 0;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        p {
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 24px;
            font-size: 16px;
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .text-muted { color: #718096; }
        .mb-0 { margin-bottom: 0; }
        
        /* Buttons */
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 10px 0 24px;
            text-align: center;
            box-shadow: 0 4px 14px 0 rgba(255, 111, 40, 0.39);
        }
        
        /* Footer */
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 30px;
            border-top: 1px solid #edf2f7;
        }
        
        .footer p {
            color: #a0aec0;
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        /* Responsive Breakpoints */
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 20px 0 !important; }
            .container { border-radius: 0 !important; width: 100% !important; max-width: 100% !important; border-left: none; border-right: none;}
            .content { padding: 30px 20px !important; }
            h1 { font-size: 24px !important; }
            p { font-size: 15px !important; }
            .btn { width: 100% !important; display: block !important; box-sizing: border-box; text-align: center; }
        }
        @media (prefers-color-scheme: dark) {
            body, .wrapper { background-color: #f3f6fb !important; }
            .container { background-color: #ffffff !important; }
        }
    </style>
    @yield('extra_css')
</head>
<body style="margin:0; padding:0; background-color:#f3f6fb; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <!-- Preheader for Inbox Preview -->
    <div style="display: none; max-height: 0px; overflow: hidden; opacity: 0; mso-hide: all;">
        @yield('preheader', 'Important update from ' . config('app.name'))
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="wrapper" role="presentation" bgcolor="#f3f6fb">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <!-- Main Content Container -->
                <table cellpadding="0" cellspacing="0" border="0" class="container" role="presentation" width="100%" style="background-color: #ffffff; border-radius: 12px; border: 1px solid #edf2f7; overflow: hidden; max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td class="content" style="padding: 40px; text-align: left; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; color: #4a5568; line-height: 1.6;">
                            @yield('content')
                        </td>
                    </tr>
                </table>
                
                @yield('footer_section')
                
                <!-- Footer Container -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto;" role="presentation">
                    <tr>
                        <td class="footer" style="padding: 30px; text-align: center; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #a0aec0;">
                            <p style="margin: 0; margin-bottom: 8px; color: #a0aec0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                            <p style="margin: 0; color: #a0aec0;">This is an automated system email. Please do not reply directly.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
