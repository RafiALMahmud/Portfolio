<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1100px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:16px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #2a2a2a;text-align:left}
        .btn{background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block}
        .btn-sm{padding:6px 10px;font-size:14px}
        .role{display:inline-block;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:600}
        .role.admin{background:#4d1a1a;color:#ffb4b4}
        .role.user{background:#1a4d1a;color:#90ee90}
        a{color:#e7d2b8}
        .success{color:#90ee90;font-size:14px;margin:8px 0}
        textarea{width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;resize:vertical}
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
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
        
        <h1>Users Management</h1>
        
        @if(session('status'))
            <div class="success">{{ session('status') }}</div>
        @endif

        <div style="margin-bottom:16px">
            <a href="{{ route('admin.users.create') }}" class="btn">Create New User</a>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#5a1a1a;color:#ffb4b4">Delete</button>
                            </form>
                            @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.message', $user) }}" style="display:inline;">
                                @csrf
                                <textarea name="message" placeholder="Type message..." rows="2" style="width:200px;margin-bottom:8px"></textarea><br>
                                <button type="submit" class="btn btn-sm">Send Message</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:12px">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>
</html>
