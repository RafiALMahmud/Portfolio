<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - L'essence</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1100px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:16px}
        .muted{color:#9f9f9f}
        table{width:100%;border-collapse:collapse;margin-top:16px}
        th,td{padding:12px;border-bottom:1px solid #2a2a2a;text-align:left}
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
        
        <h1>Admin dashboard</h1>
        <div style="display:flex;gap:8px;margin:8px 0 16px">
            <a href="{{ route('admin.users.index') }}" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600">Manage Users</a>
            <a href="{{ route('admin.products.index') }}" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600">Manage Products</a>
            <a href="{{ route('admin.products.create') }}" style="background:#c4b18f;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600">Add Product</a>
            <a href="{{ route('admin.orders.index') }}" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600">Manage Orders</a>
            <a href="{{ route('admin.messages.index') }}" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600">Messages</a>
        </div>
        
        @if(session('status'))
            <div style="color:#90ee90;margin-bottom:16px">{{ session('status') }}</div>
        @endif

        <div class="grid">
            <div class="card"><div class="muted">Total Orders</div><div style="font-size:28px;font-weight:700">{{ $totalOrders }}</div></div>
            <div class="card"><div class="muted">Pending Orders</div><div style="font-size:28px;font-weight:700">{{ $pendingOrders }}</div></div>
            <div class="card"><div class="muted">Users</div><div style="font-size:28px;font-weight:700">{{ $totalUsers }}</div></div>
            <div class="card"><div class="muted">Products</div><div style="font-size:28px;font-weight:700">{{ $totalProducts }}</div></div>
        </div>

        <div class="card" style="margin-top:16px">
            <h2 style="margin:0 0 16px">Admin Account Management</h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <h3 style="margin:0 0 12px">Profile Information</h3>
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">Full name</label>
                        <input name="name" value="{{ auth()->user()->name }}" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">Phone</label>
                        <input name="phone" value="{{ auth()->user()->phone }}" style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">Address</label>
                        <input name="present_address" value="{{ auth()->user()->present_address }}" style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <button type="submit" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;border:none;font-weight:600;margin-top:12px;cursor:pointer">Update Profile</button>
                    </form>
                </div>
                <div>
                    <h3 style="margin:0 0 12px">Change Password</h3>
                    <form method="POST" action="{{ route('account.password') }}">
                        @csrf
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">Current Password</label>
                        <input type="password" name="current_password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">New Password</label>
                        <input type="password" name="password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <label style="display:block;margin:8px 0 4px;color:#cfcfcf">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box">
                        
                        <button type="submit" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;border:none;font-weight:600;margin-top:12px;cursor:pointer">Change Password</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:16px">
            <h2 style="margin:0 0 8px">Recent orders</h2>
            @if($recentOrders->isEmpty())
                <div class="muted">No orders yet.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ optional($order->user)->name ?? 'Guest' }}</td>
                            <td>{{ ucfirst($order->status ?? 'pending') }}</td>
                            <td>৳{{ number_format($order->grand_total ?? 0, 2) }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>


