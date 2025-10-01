<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1000px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:20px;margin-bottom:20px}
        .order-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #2a2a2a}
        .order-number{font-size:24px;font-weight:700;color:#e7d2b8}
        .status-badge{padding:8px 16px;border-radius:6px;font-size:14px;font-weight:600;text-transform:uppercase}
        .status-pending{background:#fbbf24;color:#1f2937}
        .status-delivered{background:#10b981;color:#ffffff}
        .status-could-not-be-delivered{background:#ef4444;color:#ffffff}
        .order-details{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
        .detail-group h3{margin:0 0 8px;color:#e7d2b8;font-size:16px}
        .detail-group p{margin:4px 0;color:#9f9f9f}
        .items-table{width:100%;border-collapse:collapse;margin-top:16px}
        .items-table th,.items-table td{padding:12px;border-bottom:1px solid #2a2a2a;text-align:left}
        .items-table th{background:#1a1a1a;color:#e7d2b8;font-weight:600}
        .total-section{background:#1a1a1a;padding:16px;border-radius:8px;margin-top:20px}
        .total-row{display:flex;justify-content:space-between;margin:8px 0}
        .total-row.final{font-weight:700;font-size:18px;color:#e7d2b8;border-top:1px solid #2a2a2a;padding-top:8px}
        .btn{background:#e7d2b8;color:#111;padding:8px 16px;border-radius:6px;border:none;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block;margin-right:8px}
        .btn:hover{background:#d4c4a8}
        .btn-danger{background:#ef4444;color:#ffffff}
        .btn-danger:hover{background:#dc2626}
        .alert{background:#1f2937;border:1px solid #374151;color:#d1d5db;padding:12px;border-radius:6px;margin-bottom:16px}
        .alert-success{background:#064e3b;border-color:#10b981;color:#a7f3d0}
        .alert-error{background:#7f1d1d;border-color:#ef4444;color:#fca5a5}
        .back-link{color:#e7d2b8;text-decoration:none;margin-bottom:20px;display:inline-block}
        .back-link:hover{color:#d4c4a8}
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
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s; margin-right: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="back-link">← Back to Orders</a>
        </div>
        
        <h1>Order Details</h1>
        
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="order-header">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <span class="status-badge status-{{ str_replace(' ', '-', $order->status) }}">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="order-details">
                <div class="detail-group">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                </div>
                
                <div class="detail-group">
                    <h3>Order Information</h3>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Payment Gateway:</strong> {{ ucfirst($order->payment_gateway ?? 'N/A') }}</p>
                    <p><strong>Total Items:</strong> {{ $order->quantity }}</p>
                    <p><strong>Order Status:</strong> {{ $order->status_label }}</p>
                </div>
            </div>

            <h3>Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item['product_name'] ?? $item['title'] ?? 'Unknown Product' }}</td>
                        <td>{{ $item['variant_name'] ?? $item['variant'] ?? 'Default' }}</td>
                        <td>৳{{ number_format($item['price'] ?? 0, 2) }}</td>
                        <td>{{ $item['qty'] ?? 0 }}</td>
                        <td>৳{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 0), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>৳{{ number_format($order->shipping, 2) }}</span>
                </div>
                <div class="total-row final">
                    <span>Grand Total:</span>
                    <span>৳{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>

            <div style="margin-top:20px;padding-top:20px;border-top:1px solid #2a2a2a;">
                <h3>Update Order Status</h3>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <select name="status" style="padding:8px;background:#222;color:#e9e9e9;border:1px solid #444;border-radius:6px;margin-right:8px;">
                        <option value="{{ \App\Models\Order::STATUS_PENDING }}" {{ $order->status === \App\Models\Order::STATUS_PENDING ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="{{ \App\Models\Order::STATUS_RECEIVED }}" {{ $order->status === \App\Models\Order::STATUS_RECEIVED ? 'selected' : '' }}>
                            Received
                        </option>
                        <option value="{{ \App\Models\Order::STATUS_UNAVAILABLE }}" {{ $order->status === \App\Models\Order::STATUS_UNAVAILABLE ? 'selected' : '' }}>
                            Unavailable
                        </option>
                    </select>
                    <button type="submit" class="btn">Update Status</button>
                </form>
                
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display:inline;" onsubmit="return confirm('Are you sure? This will notify the user and delete the order.')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="message" value="Your order has been cancelled and removed from our system.">
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>



