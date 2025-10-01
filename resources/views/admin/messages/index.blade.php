<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1100px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:16px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #2a2a2a;text-align:left}
        .btn{background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block}
        .btn-sm{padding:6px 10px;font-size:14px}
        a{color:#e7d2b8}
        .success{color:#90ee90;font-size:14px;margin:8px 0}
        .nav{display:flex;gap:8px;margin:8px 0 16px}
        .nav a{background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600}
        .nav a:hover{background:#c4b18f}
        .message-text{white-space:pre-wrap;max-width:300px;word-wrap:break-word}
        .status-read{color:#90ee90}
        .status-unread{color:#fbbf24;font-weight:600}
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
        
        <h1>Messages from users</h1>
        <div class="nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.users.index') }}">Users</a>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
        </div>
        
        @if(session('status'))
            <div class="success">{{ session('status') }}</div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($notifications as $notification)
                    @php($data = $notification->data)
                    <tr>
                        <td>{{ $data['user_name'] ?? 'Unknown' }}</td>
                        <td class="message-text">{{ $data['message'] ?? '' }}</td>
                        <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                        <td class="{{ $notification->read_at ? 'status-read' : 'status-unread' }}">
                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                        </td>
                        <td>
                            @if(!$notification->read_at)
                            <form method="POST" action="{{ route('admin.messages.read', $notification->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm">Mark as read</button>
                            </form>
                            @else
                            <span style="color:#9f9f9f">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:16px;text-align:center;color:#9f9f9f">No messages yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $notifications->links() }}
        </div>
    </div>
</body>
</html>


