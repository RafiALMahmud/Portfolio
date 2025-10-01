@extends('layouts.app')

@section('title', 'Verify Your Email - L\'essence')

@section('styles')
<style>
    .verify-container { 
        max-width: 560px; 
        margin: 0 auto; 
        padding: 2rem;
    }
    .verify-title { 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        font-weight: 600; 
        margin: 0 0 2rem;
        color: #e7d2b8;
        text-align: center;
    }
    .verify-card { 
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #222; 
        border-radius: 12px; 
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    .verify-message {
        color: #cfcfcf;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    .btn-resend {
        background: linear-gradient(45deg, #e7d2b8, #d4af37);
        color: #111;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .btn-resend:hover {
        background: linear-gradient(45deg, #d4af37, #e7d2b8);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(231, 210, 184, 0.3);
    }
    .status-message {
        color: #90ee90;
        background: #1a2a1a;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #2a4a2a;
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .verify-container { padding: 1rem; }
        .verify-title { font-size: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="verify-container">
    <a href="{{ route('home') }}" class="back-button">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back to Home
    </a>
    
    <h1 class="verify-title">Verify Your Email</h1>
    
    <div class="verify-card">
        <div class="verify-message">
            We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>. 
            Please check your email and click the link to activate your account.
        </div>
        
        @if (session('status') === 'sent')
            <div class="status-message">
                A new verification link has been sent to your email address.
            </div>
        @endif
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
            <button class="btn-resend" type="submit">Resend Verification Email</button>
        </form>
    </div>
</div>
@endsection


