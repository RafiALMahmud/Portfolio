<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:720px;margin:48px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:24px}
        label{display:block;margin:12px 0 6px;color:#cfcfcf}
        input,select{width:100%;padding:12px 14px;border-radius:8px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .btn{background:#e7d2b8;color:#111;padding:12px 16px;border-radius:8px;border:none;font-weight:600;margin-top:16px;cursor:pointer}
        .error{color:#ffb4b4;font-size:14px}
        a{color:#e7d2b8}
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('admin.users.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Users
            </a>
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Home
            </a>
        </div>
        
        <h1>Create New User</h1>
        <div class="card">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <label for="name">Full name</label>
                <input id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror

                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror

                <div class="row">
                    <div>
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <label for="present_address">Address</label>
                <input id="present_address" name="present_address" value="{{ old('present_address') }}" required>
                @error('present_address')<div class="error">{{ $message }}</div>@enderror

                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror

                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>

                <button class="btn" type="submit">Create User</button>
            </form>
        </div>
        
        <p style="margin-top:16px;color:#9f9f9f">
            <a href="{{ route('admin.users.index') }}">Back to users</a>
        </p>
    </div>
</body>
</html>
