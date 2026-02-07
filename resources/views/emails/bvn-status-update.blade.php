<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service_name }} Status Update</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Responsive Wrapper */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 20px 10px 40px;
        }
        
        /* Responsive Container */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #FF6F28 0%, #FF5325 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.025em;
            line-height: 1.3;
        }
        
        /* Content Area */
        .content {
            padding: 30px 20px;
        }
        
        @media (min-width: 480px) {
            .content {
                padding: 40px 30px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
        
        /* Text Styles */
        .content p {
            color: #475569;
            line-height: 1.7;
            font-size: 15px;
            margin-bottom: 20px;
        }
        
        @media (min-width: 480px) {
            .content p {
                font-size: 16px;
            }
        }
        
        /* Details Card */
        .details-card {
            background-color: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #FF6F28;
        }
        
        @media (min-width: 480px) {
            .details-card {
                padding: 25px;
            }
        }
        
        /* Detail Rows - Stack on mobile */
        .detail-row {
            margin-bottom: 12px;
            display: block;
        }
        
        @media (min-width: 480px) {
            .detail-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
        }
        
        .detail-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            display: block;
            margin-bottom: 3px;
        }
        
        @media (min-width: 480px) {
            .detail-label {
                display: inline-block;
                margin-bottom: 0;
            }
        }
        
        .detail-value {
            color: #1e293b;
            font-weight: 600;
            font-size: 15px;
            display: block;
            word-break: break-word;
        }
        
        @media (min-width: 480px) {
            .detail-value {
                display: inline-block;
                font-size: 16px;
            }
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }
        
        @media (min-width: 480px) {
            .status-badge {
                margin-top: 0;
            }
        }
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-resolved, .status-successful { background-color: #d1fae5; color: #065f46; }
        .status-rejected, .status-failed { background-color: #fee2e2; color: #991b1b; }
        
        /* Comment Box */
        .comment-box {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            padding: 18px;
            border-radius: 8px;
            margin-top: 25px;
        }
        
        /* Action Button */
        .btn-action {
            display: block;
            background: #FF6F28;
            color: #ffffff !important;
            padding: 14px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            margin: 20px auto 0;
            text-align: center;
            max-width: 300px;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }
        
        .btn-action:hover {
            background-color: #E65A20;
        }
        
        @media (min-width: 480px) {
            .btn-action {
                display: inline-block;
                width: auto;
                max-width: none;
                padding: 14px 28px;
            }
        }
        
        /* Status Message */
        .status-message {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            text-align: center;
        }
        
        .status-icon {
            font-size: 24px;
            display: block;
            margin-bottom: 10px;
        }
        
        .status-text {
            margin: 0;
            font-weight: 600;
            line-height: 1.4;
        }
        
        /* Support Section */
        .support-section {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 25px 20px;
            text-align: center;
        }
        
        .support-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            display: block;
            font-size: 16px;
        }
        
        /* Social Links */
        .social-links {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        
        .social-link-item {
            color: #FF6F28;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }
        
        .social-link-item:hover {
            color: #E65A20;
            text-decoration: underline;
        }
        
        /* Hotline */
        .hotline {
            padding: 12px 20px;
            background-color: #eff6ff;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .hotline span {
            color: #1e40af;
            font-size: 13px;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 25px 20px;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.5;
        }
        
        @media (min-width: 480px) {
            .footer {
                font-size: 13px;
            }
        }
        
        .footer p {
            margin: 0 0 10px 0;
        }
        
        /* Print Styles */
        @media print {
            body {
                background-color: white !important;
            }
            
            .container {
                box-shadow: none !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ $service_name }} Update</h1>
            </div>
            
            <div class="content">
                <p>Hello <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>
                <p>There has been a status update regarding your <strong>{{ $service_name }}</strong> request. Please find the details below:</p>
                
                <div class="details-card">
                    <div class="detail-row">
                        <span class="detail-label">Service Type:</span>
                        <span class="detail-value">{{ $service_name }}</span>
                    </div>
                    
                    @if(isset($bvn))
                    <div class="detail-row">
                        <span class="detail-label">BVN Number:</span>
                        <span class="detail-value">{{ $bvn }}</span>
                    </div>
                    @endif
                    
                    @if(isset($nin))
                    <div class="detail-row">
                        <span class="detail-label">NIN Number:</span>
                        <span class="detail-value">{{ $nin }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-row">
                        <span class="detail-label">Reference ID:</span>
                        <span class="detail-value">#{{ $reference }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Current Status:</span>
                        <span class="status-badge status-{{ strtolower($status) }}">{{ $status }}</span>
                    </div>
                </div>

                @if($comment)
                <div class="comment-box">
                    <p style="margin: 0 0 10px 0; color: #92400e; font-weight: 700; font-size: 14px;">STAFF REMARKS:</p>
                    <p style="margin: 0; color: #78350f; line-height: 1.5;">{{ $comment }}</p>
                </div>
                @endif

                @if($file_url)
                <div style="text-align: center;">
                    <a href="{{ url($file_url) }}" class="btn-action" target="_blank">📥 Download Your Document</a>
                </div>
                @endif

                <div class="status-message">
                    @if(strtolower($status) === 'resolved' || strtolower($status) === 'successful')
                        <span class="status-icon">✅</span>
                        <p class="status-text" style="color: #065f46;">Your request has been successfully completed!</p>
                    @elseif(strtolower($status) === 'rejected' || strtolower($status) === 'failed')
                        <span class="status-icon">❌</span>
                        <p class="status-text" style="color: #991b1b;">Your request could not be processed at this time.</p>
                    @else
                        <span class="status-icon">🔔</span>
                        <p class="status-text" style="color: #1e40af;">We are actively working on your request.</p>
                    @endif
                </div>
            </div>

            <div class="support-section">
                <span class="support-title">Need Assistance?</span>
                <p style="font-size: 14px; margin-bottom: 20px;">Join our community or follow us for updates.</p>
                
                <div class="social-links">
                    <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link-item">💬 Join WhatsApp Group</a>
                    <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link-item">🔵 Follow Us on Facebook</a>
                </div>
                
                <div class="hotline">
                    <span>Support Hotline: 08064333983</span>
                </div>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
                <p>{{ config('app.url') }}</p>
            </div>
        </div>
    </div>
</body>
</html>