<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - L'essence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:960px;margin:48px auto;padding:24px}
        h1{font-family:'Playfair Display',serif;font-weight:600;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:24px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px;border-bottom:1px solid #2a2a2a;text-align:left}
        th{color:#cfcfcf;font-weight:600}
        a{color:#e7d2b8}
        .muted{color:#9f9f9f}
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
            <a href="{{ route('account') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Account
            </a>
        </div>
        
        <h1>My Orders</h1>
        
        <!-- Current Orders -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1rem; color: #e7d2b8;">Current Orders</h2>
            @php($currentOrders = auth()->user()->orders()->whereNotIn('status', [\App\Models\Order::STATUS_RECEIVED])->latest()->get())
            @if($currentOrders->isEmpty())
                <p class="muted">You have no current orders.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; 
                                        @if($order->status === 'unconfirmed') background: #6b7280; color: #ffffff;
                                        @elseif($order->status === 'pending') background: #fbbf24; color: #1f2937;
                                        @elseif($order->status === 'unavailable') background: #ef4444; color: #ffffff;
                                        @endif">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->grand_total ?? 0, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" style="color: #e7d2b8; text-decoration: none; font-size: 14px;">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Order History -->
        <div class="card">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1rem; color: #e7d2b8;">Order History</h2>
            @php($orderHistory = auth()->user()->orders()->where('status', \App\Models\Order::STATUS_RECEIVED)->latest()->get())
            @if($orderHistory->isEmpty())
                <p class="muted">You have no completed orders yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderHistory as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; background: #10b981; color: #ffffff;">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->grand_total ?? 0, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" style="color: #e7d2b8; text-decoration: none; font-size: 14px;">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <p class="muted" style="margin-top:16px"><a href="{{ route('account') }}">Back to account</a></p>
    </div>
</body>
</html>


