@extends('layouts.email')

@section('title', 'Welcome to Arewa Smart')

@section('header_title', 'Welcome to Arewa Smart!')

@section('extra_css')
<style>
    .credentials-card {
        background-color: #f8fafc;
        padding: 30px;
        border-radius: 12px;
        margin: 25px 0;
        border: 1px dashed #FF6F28;
        text-align: center;
    }
    .credential-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }
    .credential-value {
        color: #1e293b;
        font-weight: 700;
        font-size: 18px;
        font-family: 'Courier New', Courier, monospace;
        display: block;
        margin-bottom: 15px;
    }
    .warning-banner {
        background-color: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 20px;
        border-radius: 8px;
        margin-top: 25px;
    }
    .warning-text {
        color: #991b1b;
        font-size: 14px;
        font-weight: 600;
        margin: 0;
    }
    .support-footer {
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 35px;
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
    <p>Hello <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,</p>
    <p>We are thrilled to have you join the <strong>Arewa Smart</strong> family. Your account is ready, and you can now explore our range of smart digital services.</p>
    
    <p>Here are your secure login credentials to get started:</p>
    
    <div class="credentials-card">
        <span class="credential-label">Login Email</span>
        <span class="credential-value">{{ $user->email }}</span>
        
        <span class="credential-label">Temporary Password</span>
        <span class="credential-value">{{ $password }}</span>
        
        <a href="{{ route('login') }}" class="btn">Access My Dashboard</a>
    </div>

    <div class="warning-banner">
        <p class="warning-text">⚠️ For your security, please change your temporary password immediately after your first successful login.</p>
    </div>

    <p style="margin-top: 30px;">If you have any questions as you settle in, our community and support team are always available to help.</p>

    @include('emails.partials.referral_advert')
@endsection

@section('footer_section')
    <div class="support-footer">
        <span class="support-title">Connect With Us</span>
        
        <div style="margin-bottom: 25px;">
            <a href="https://chat.whatsapp.com/DbXtTP0VPW90YDqKaKBsl3" class="social-link">💬 Join WhatsApp Community</a>
            <a href="https://www.facebook.com/share/184dBwK8HX/" class="social-link">🔵 Facebook Official</a>
        </div>
        
        <div style="padding: 12px 20px; background-color: #eff6ff; border-radius: 50px; display: inline-block;">
            <span style="color: #1e40af; font-size: 14px; font-weight: 700;">Support Desk: 08064333983</span>
        </div>
    </div>
@endsection
