@extends('layouts.email')

@section('title', 'Application Approved - ' . config('app.name'))

@section('header_title', 'Application Approved!')

@section('extra_css')
<style>
    .success-banner {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 25px;
        border-radius: 12px;
        margin: 25px 0;
        text-align: center;
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
</style>
@endsection

@section('content')
   <p>Hello <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>
    
    <div class="success-banner">
        <span style="font-size: 40px;">🎉</span>
        <p style="margin: 15px 0 0 0; font-weight: 700; color: #166534; font-size: 18px;">Congratulations!</p>
        <p style="margin: 5px 0 0 0; color: #15803d;">Your API application has been successfully reviewed and approved.</p>
    </div>
    
    <p>You now have full access to our powerful API infrastructure. We are excited to see what you build with <strong>Arewa Smart</strong>.</p>
    
    <p>Click the button below to access your developer portal and get started:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="https://api.arewasmart.com.ng" class="btn">Go to Developer Portal</a>
    </div>
    
    <p style="font-size: 14px; color: #64748b; text-align: center;">
        Direct link: <a href="https://api.arewasmart.com.ng" style="color: #FF6F28; text-decoration: none;">api.arewasmart.com.ng</a>
    </p>

    @include('emails.partials.referral_advert')
@endsection

@section('footer_section')
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
@endsection
