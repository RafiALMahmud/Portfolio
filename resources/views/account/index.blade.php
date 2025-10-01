<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - L'essence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:720px;margin:48px auto;padding:24px}
        h1{font-family:'Playfair Display',serif;font-weight:600;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:24px}
        label{display:block;margin:12px 0 6px;color:#cfcfcf}
        input{width:100%;padding:12px 14px;border-radius:8px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .btn{display:inline-block;background:#e7d2b8;color:#111;padding:12px 16px;border-radius:8px;border:none;font-weight:600;margin-top:16px;cursor:pointer}
        .muted{color:#9f9f9f;font-size:14px;margin-top:12px}
        .error{color:#ffb4b4;font-size:14px}
        a{color:#e7d2b8}
    </style>
 </head>
 <body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Home
            </a>
        </div>
        
        <h1>My account</h1>
        @if(auth()->check() && strtolower((string) auth()->user()->role) === 'admin')
            <div style="margin: 8px 0 16px">
                <a href="{{ route('admin.dashboard') }}" style="position:absolute;left:-9999px;">Admin</a>
            </div>
        @endif
        @if(session('status'))
            <div style="color:#90ee90;margin-bottom:16px">{{ session('status') }}</div>
        @endif

        <div class="card">
            <h2 style="margin:0 0 16px">Profile Information</h2>
            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                <label for="name">Full name</label>
                <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror

                <div class="row">
                    <div>
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="present_address">Address</label>
                        <input id="present_address" name="present_address" value="{{ old('present_address', auth()->user()->present_address) }}">
                        @error('present_address')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button class="btn" type="submit">Save changes</button>
            </form>
        </div>

        <div class="card" style="margin-top:16px">
            <h2 style="margin:0 0 16px">Change Password</h2>
            <form method="POST" action="{{ route('account.password') }}">
                @csrf
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
                @error('current_password')<div class="error">{{ $message }}</div>@enderror

                <div class="row">
                    <div>
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <button class="btn" type="submit">Change Password</button>
            </form>
        </div>

        <p class="muted" style="margin-top:16px">
            <a href="{{ route('account.orders') }}">View orders</a> | 
            <a href="{{ route('account.messages') }}">Messages</a>
        </p>
    </div>
 </body>
 </html>


