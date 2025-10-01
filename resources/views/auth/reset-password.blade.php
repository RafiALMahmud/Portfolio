<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - L'essence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #e9e9e9; 
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #e7d2b8;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .card {
            background: rgba(20, 20, 20, 0.95);
            border: 1px solid #222;
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .card h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #e7d2b8;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .card p {
            color: #9f9f9f;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e9e9e9;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #444;
            border-radius: 8px;
            background: #1a1a1a;
            color: #e9e9e9;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #e7d2b8;
            box-shadow: 0 0 0 3px rgba(231, 210, 184, 0.1);
        }
        
        .form-control::placeholder {
            color: #666;
        }
        
        .btn {
            width: 100%;
            background: #e7d2b8;
            color: #111;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #d4c4a8;
            transform: translateY(-1px);
        }
        
        .btn:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-link {
            display: block;
            text-align: center;
            color: #e7d2b8;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #d4c4a8;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 14px;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            color: #a7f3d0;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 0.5rem;
        }
        
        .form-group.has-error .form-control {
            border-color: #ef4444;
        }
        
        .password-requirements {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
            font-size: 12px;
            color: #9f9f9f;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 16px;
        }
        
        .password-requirements li {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">L'essence</div>
        
        <!-- Navigation -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; justify-content: center;">
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Home
            </a>
            <a href="{{ route('login.show') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Login
            </a>
        </div>
        
        <div class="card">
            <h1>Reset Password</h1>
            <p>Enter your new password below.</p>
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        class="form-control" 
                        value="{{ $email }}"
                        disabled
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Enter your new password"
                        required 
                        autofocus
                    >
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="password-requirements">
                        <strong>Password requirements:</strong>
                        <ul>
                            <li>At least 6 characters long</li>
                            <li>Must match confirmation password</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        placeholder="Confirm your new password"
                        required
                    >
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn">
                    Reset Password
                </button>
            </form>
            
            <a href="{{ route('login.show') }}" class="back-link">
                ← Back to Login
            </a>
        </div>
    </div>
</body>
</html>




