@extends('layouts.email')

@section('title', $service_name . ' Status Update')

@section('header_title', $service_name . ' Update')

@section('extra_css')
<style>
    .details-card {
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
        align-items: center;
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
        font-weight: 500;
    }
    .detail-value {
        color: #1e293b;
        font-weight: 700;
        font-size: 15px;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-processing { background-color: #dbeafe; color: #1e40af; }
    .status-resolved, .status-successful { background-color: #d1fae5; color: #065f46; }
    .status-rejected, .status-failed { background-color: #fee2e2; color: #991b1b; }
    
    .comment-box {
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        padding: 18px;
        border-radius: 8px;
        margin-top: 25px;
    }
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
    .support-section {
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 25px;
        text-align: center;
    }
    .social-link-item {
        color: #FF6F28;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin: 0 10px;
    }
</style>
@endsection

@section('content')
    <p>Hello <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>
    <p>There has been a status update regarding your <strong>{{ $service_name }}</strong> request. Please find the details below:</p>
    
    <div class="details-card">
        <div class="detail-row">
            <span class="detail-label">Service Type</span>
            <span class="detail-value">{{ $service_name }}</span>
        </div>
        
        @if(isset($bvn))
        <div class="detail-row">
            <span class="detail-label">BVN Number</span>
            <span class="detail-value">{{ $bvn }}</span>
        </div>
        @endif
        
        @if(isset($nin))
        <div class="detail-row">
            <span class="detail-label">NIN Number</span>
            <span class="detail-value">{{ $nin }}</span>
        </div>
        @endif
        
        <div class="detail-row">
            <span class="detail-label">Reference ID</span>
            <span class="detail-value">#{{ $reference }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Current Status</span>
            <span class="status-badge status-{{ strtolower($status) }}">{{ $status }}</span>
        </div>
    </div>

    @if($comment)
    <div class="comment-box">
        <p style="margin: 0 0 10px 0; color: #92400e; font-weight: 700; font-size: 14px;">STAFF REMARKS:</p>
        <p style="margin: 0; color: #783500; line-height: 1.5;">{{ $comment }}</p>
    </div>
    @endif

    @if($file_url)
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url($file_url) }}" class="btn">📥 Download Your Document</a>
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

    @include('emails.partials.referral_advert')
@endsection

@section('footer_section')
    <div class="support-section">
        <span class="support-title" style="font-weight: 700; color: #1e293b; margin-bottom: 15px; display: block;">Need Assistance?</span>
        <p style="font-size: 14px; margin-bottom: 20px;">Join our community or follow us for updates.</p>
        
        <div class="social-links">
            <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link-item">💬 WhatsApp Group</a>
            <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link-item">🔵 Official Facebook</a>
        </div>
        
        <div style="padding: 12px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block; margin-top: 15px;">
            <span style="color: #1e40af; font-size: 13px; font-weight: 700;">Support Desk: 08064333983</span>
        </div>
    </div>
@endsection