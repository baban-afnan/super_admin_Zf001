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
        word-break: break-word;
    }
</style>
@endsection

@section('content')
    <p>Hello <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>
    <p>We're writing to update you on your <strong>{{ $service_name }}</strong> ticket. Please find the details below:</p>
    
    <div class="details-card">
        <div class="detail-row">
            <span class="detail-label">Service</span>
            <span class="detail-value">{{ $service_name }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ticket ID</span>
            <span class="detail-value">#{{ $ticket_id }}</span>
        </div>
        @if($batch_id)
        <div class="detail-row">
            <span class="detail-label">Batch ID</span>
            <span class="detail-value">#{{ $batch_id }}</span>
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Reference</span>
            <span class="detail-value">{{ $reference }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ticket Status</span>
            <span class="status-badge status-{{ strtolower($status) }}">{{ $status }}</span>
        </div>
    </div>

    @if($comment)
    <div class="comment-box">
        <p style="margin: 0 0 10px 0; color: #92400e; font-weight: 700; font-size: 14px;">AGENT RESPONSE:</p>
        <div style="margin: 0; color: #783500; line-height: 1.5; white-space: pre-wrap; font-family: 'Courier New', Courier, monospace; font-size: 14px;">{{ $comment }}</div>
    </div>
    @endif

    @if($file_url)
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url($file_url) }}" class="btn">📥 Download Attached Document</a>
    </div>
    @endif

    <div style="margin-top: 30px; padding: 20px; background-color: #f8fafc; border-radius: 8px; text-align: center;">
        @if(strtolower($status) === 'resolved' || strtolower($status) === 'successful')
            <span style="font-size: 24px;">🎯</span>
            <p style="margin: 10px 0 0 0; font-weight: 600; color: #065f46;">Your issue has been resolved successfully.</p>
        @elseif(strtolower($status) === 'rejected' || strtolower($status) === 'failed')
            <span style="font-size: 24px;">🚫</span>
            <p style="margin: 10px 0 0 0; font-weight: 600; color: #991b1b;">Your request was not successful. Review the remarks above.</p>
        @else
            <span style="font-size: 24px;">👨‍💻</span>
            <p style="margin: 10px 0 0 0; font-weight: 600; color: #1e40af;">Our team is currently investigating your request.</p>
        @endif
    </div>

    @include('emails.partials.referral_advert')
@endsection

@section('footer_section')
    <div class="support-section" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 30px; text-align: center;">
        <span class="support-title" style="font-weight: 700; color: #1e293b; margin-bottom: 15px; display: block;">Need Real-time Support?</span>
        
        <div style="margin-bottom: 20px;">
            <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" style="color: #FF6F28; text-decoration: none; font-weight: 600; font-size: 14px; margin: 0 10px;">💬 WhatsApp Community</a>
            <a href="https://www.facebook.com/share/184dBwK8HX/" style="color: #FF6F28; text-decoration: none; font-weight: 600; font-size: 14px; margin: 0 10px;">🔵 Official Facebook</a>
        </div>
        
        <div style="padding: 12px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block;">
            <span style="color: #1e40af; font-size: 13px; font-weight: 700;">Technical Support: 08064333983</span>
        </div>
    </div>
@endsection
