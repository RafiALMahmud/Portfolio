@extends('layouts.app')

@section('title', 'Create Account - L\'essence')

@section('styles')
<style>
    .auth-container { 
        max-width: 560px; 
        margin: 0 auto; 
        padding: 2rem;
    }
    .auth-title { 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        font-weight: 600; 
        margin: 0 0 2rem;
        color: #e7d2b8;
        text-align: center;
    }
    .auth-card { 
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #222; 
        border-radius: 12px; 
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cfcfcf;
        font-weight: 500;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
        background: #0f0f0f;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    .form-input:focus {
        outline: none;
        border-color: #e7d2b8;
        box-shadow: 0 0 0 2px rgba(231, 210, 184, 0.2);
    }
    .form-input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #0f0f0f inset;
        -webkit-text-fill-color: #fff;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 560px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    .btn-submit {
        background: linear-gradient(45deg, #e7d2b8, #d4af37);
        color: #111;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        background: linear-gradient(45deg, #d4af37, #e7d2b8);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(231, 210, 184, 0.3);
    }
    .terms-notice {
        color: #9f9f9f;
        font-size: 0.9rem;
        margin-top: 1rem;
        padding: 1rem;
        background: #1a1a1a;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
    }
    .auth-links {
        text-align: center;
        color: #9f9f9f;
        font-size: 0.9rem;
    }
    .auth-links a {
        color: #e7d2b8;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    .auth-links a:hover {
        color: #d4c4a8;
    }
    .error-message {
        color: #ffb4b4;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        background: #2a1a1a;
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #4a2a2a;
    }
    @media (max-width: 768px) {
        .auth-container { padding: 1rem; }
        .auth-title { font-size: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <a href="{{ route('home') }}" class="back-button">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back to Home
    </a>
    
    <h1 class="auth-title">Create Your Account</h1>
    
    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="form-input" required>
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="present_address" class="form-label">Address</label>
                    <input id="present_address" name="present_address" value="{{ old('present_address') }}" class="form-input" required>
                    @error('present_address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-input" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" required>
            </div>

            <button class="btn-submit" type="submit">Create Account</button>
            
            <div class="terms-notice">
                By creating an account, you agree to verify your email address before checkout.
            </div>
        </form>
    </div>
    
    <p class="auth-links">
        Already have an account? <a href="{{ route('login.show') }}">Sign in</a>
    </p>
</div>
@endsection

