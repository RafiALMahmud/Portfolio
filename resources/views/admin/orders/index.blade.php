<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1200px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:16px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #2a2a2a;text-align:left}
        a{color:#e7d2b8;text-decoration:none}
        .status-filter{margin-bottom:16px;display:flex;gap:8px;align-items:center}
        .status-filter select{padding:8px;border-radius:6px;background:#222;color:#e9e9e9;border:1px solid #444}
        .status-badge{padding:4px 8px;border-radius:4px;font-size:12px;font-weight:600;text-transform:uppercase}
        .status-unconfirmed{background:#6b7280;color:#ffffff}
        .status-pending{background:#fbbf24;color:#1f2937}
        .status-received{background:#10b981;color:#ffffff}
        .status-unavailable{background:#ef4444;color:#ffffff}
        .btn{background:#e7d2b8;color:#111;padding:6px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block}
        .btn:hover{background:#d4c4a8}
        .btn-sm{padding:4px 8px;font-size:12px}
        .btn-danger{background:#ef4444;color:#ffffff}
        .btn-danger:hover{background:#dc2626}
        .actions{display:flex;gap:4px}
        .alert{background:#1f2937;border:1px solid #374151;color:#d1d5db;padding:12px;border-radius:6px;margin-bottom:16px}
        .alert-success{background:#064e3b;border-color:#10b981;color:#a7f3d0}
        .alert-error{background:#7f1d1d;border-color:#ef4444;color:#fca5a5}
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s; margin-right: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Home
            </a>
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
        
        <h1>{{ isset($isHistory) && $isHistory ? 'Order History' : 'Active Orders' }}</h1>
        <div style="margin-bottom:12px; display:flex; gap:8px;">
            <a href="{{ route('admin.orders.index') }}" class="btn {{ !isset($isHistory) ? '' : '' }}">Active</a>
            <a href="{{ route('admin.orders.history') }}" class="btn">History</a>
        </div>
        
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="status-filter">
                <label for="status-filter">Filter by Status:</label>
                @if(!isset($isHistory))
                    <select id="status-filter" onchange="filterByStatus()">
                        <option value="">Active Orders</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th>Total (৳)</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ optional($order->user)->name ?? 'Guest' }}</td>
                        <td>
                            <span class="status-badge status-{{ str_replace(' ', '-', $order->status) }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>{{ $order->quantity }} items</td>
                        <td>৳{{ number_format($order->grand_total ?? 0, 2) }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <div class="actions">
                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" style="padding:4px;background:#222;color:#e9e9e9;border:1px solid #444;border-radius:4px;">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm">View</a>
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display:inline;" onsubmit="return confirm('Are you sure? This will notify the user and delete the order.')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="message" value="Your order has been cancelled and removed from our system.">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:12px">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <script>
        function filterByStatus() {
            const status = document.getElementById('status-filter').value;
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        }
    </script>
</body>
</html>


