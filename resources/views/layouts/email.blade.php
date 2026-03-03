<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7fa;
            padding: 40px 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .content {
            padding: 40px 35px;
            color: #334155;
        }
        .content p {
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
            font-size: 13px;
        }
        .btn {
            display: inline-block;
            background: #FF6F28;
            color: #ffffff !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        @media screen and (max-width: 600px) {
            .wrapper { padding: 20px 0; }
            .container { border-radius: 0; }
            .content { padding: 30px 20px; }
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                @if(file_exists(public_path('assets/img/logo/logo.png')))
                    <img src="{{ $message->embed(public_path('assets/img/logo/logo.png')) }}" alt="{{ config('app.name') }}">
                @endif
                <h1>@yield('header_title', config('app.name'))</h1>
            </div>
            
            <div class="content">
                @yield('content')
            </div>

            @yield('footer_section')

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p style="margin-top: 8px;">This is an automated system email. Please do not reply directly.</p>
            </div>
        </div>
    </div>
</body>
</html>
