@extends('layouts.email')

@section('title', config('app.name') . ' Notification')

@section('header_title')
    @if(is_array($mail_data) && isset($mail_data['amount']))
        Payment Update
    @else
        Arewa Smart Notifications
    @endif
@endsection

@section('extra_css')
<style>
    .notification-card {
        background-color: #f8fafc;
        padding: 25px;
        border-radius: 12px;
        margin: 25px 0;
        border-left: 4px solid #FF6F28;
    }
    .detail-row {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 8px;
    }
    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    .detail-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }
    .detail-value {
        color: #1e293b;
        font-weight: 700;
        font-size: 14px;
    }
    .embedded-img-container {
        margin-top: 30px;
        text-align: center;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .embedded-img-container img {
        max-width: 100%;
        height: auto;
        display: block;
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
    .social-link {
        display: inline-block;
        margin: 0 10px;
        color: #FF6F28;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endsection

@section('content')
    <p>Hello,</p>
    
    @if(is_array($mail_data) && isset($mail_data['amount']))
        <p>We've processed a new transaction on your account. Here are the latest details:</p>
        
        <div class="notification-card">
            <div class="detail-row">
                <span class="detail-label">Service Type</span>
                <span class="detail-value">{{ $mail_data['type'] ?? 'Transaction' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Paid</span>
                <span class="detail-value" style="color: #059669;">₦{{ isset($mail_data['amount']) ? number_format((float)$mail_data['amount'], 2) : '0.00' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Reference ID</span>
                <span class="detail-value">{{ $mail_data['ref'] ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Channel/Bank</span>
                <span class="detail-value">{{ $mail_data['bankName'] ?? 'Internal' }}</span>
            </div>
        </div>
    @else
        <div class="notification-card" style="background-color: #fffbeb; border-color: #fbbf24;">
            <p style="margin: 0; color: #92400e; line-height: 1.6;">{!! nl2br(e($mail_data)) !!}</p>
        </div>
    @endif

    @if(isset($imagePath) && $imagePath)
        <div class="embedded-img-container">
            @php
                $src = $imagePath;
                // If it's a relative path (not starting with http), try to embed it
                if (strpos($imagePath, 'http') !== 0) {
                    $cleanPath = ltrim($imagePath, '/');
                    // Check if 'storage/' prefix is present and remove it for storage_path
                    if (strpos($cleanPath, 'storage/') === 0) {
                        $cleanPath = substr($cleanPath, 8);
                    }
                    
                    try {
                        $src = $message->embed(storage_path('app/public/' . $cleanPath));
                    } catch (\Exception $e) {
                        // Fallback to asset URL if embedding fails
                        $src = asset('storage/' . $cleanPath);
                    }
                }
            @endphp
            <img src="{{ $src }}" alt="Notification Details">
        </div>
    @endif

    <p style="margin-top: 30px;">Log in to your dashboard to view your full transaction history or update your settings.</p>

    @include('emails.partials.referral_advert')
@endsection

@section('footer_section')
    <div class="support-section">
        <span class="support-title">Need Help? Contact Us</span>
        
        <div style="margin-bottom: 20px;">
            <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link">💬 WhatsApp Group</a>
            <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link">🔵 Official Facebook</a>
        </div>
        
        <div style="padding: 10px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block;">
            <span style="color: #1e40af; font-size: 13px; font-weight: 700;">Support Desk: 08064333983</span>
        </div>
    </div>
@endsection
