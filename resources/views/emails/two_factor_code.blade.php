@extends('layouts.email')

@section('title', 'Security Verification - ' . config('app.name'))

@section('header_title', 'Identity Verification')

@section('extra_css')
<style>
    .otp-container {
        background-color: #f8fafc;
        padding: 30px;
        border-radius: 12px;
        margin: 30px 0;
        border: 2px solid #e2e8f0;
        text-align: center;
    }
    .otp-code {
        color: #FF6F28;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: 8px;
        font-family: 'Courier New', Courier, monospace;
        display: block;
    }
    .expiry-badge {
        display: inline-block;
        background-color: #fef2f2;
        color: #ef4444;
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 12px;
        margin-top: 15px;
    }
</style>
@endsection

@section('content')
    <p>Hello,</p>
    <p>You are receiving this code because a login or sensitive action was initiated on your <strong>Arewa Smart</strong> account.</p>
    
    <div class="otp-container">
        <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; display: block;">Your Verification Code</span>
        <span class="otp-code">{{ $code }}</span>
        <span class="expiry-badge">Expires in 10 minutes</span>
    </div>

    <p>If you did not initiate this request, please ignore this email or contact support if you have concerns about your account security.</p>

    @include('emails.partials.referral_advert')
@endsection
