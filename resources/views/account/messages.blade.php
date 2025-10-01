<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - L'essence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:960px;margin:48px auto;padding:24px}
        h1{font-family:'Playfair Display',serif;font-weight:600;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:24px}
        .message{background:#1a1a1a;border:1px solid #2a2a2a;border-radius:8px;padding:16px;margin-bottom:12px}
        .message-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
        .message-from{color:#e7d2b8;font-weight:600}
        .message-date{color:#9f9f9f;font-size:14px}
        .message-content{color:#cfcfcf;line-height:1.5}
        .unread{border-left:4px solid #e7d2b8}
        a{color:#e7d2b8}
        .muted{color:#9f9f9f;text-align:center;padding:2rem}
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Messages</h1>
        <div class="card">
            @if(session('status'))
                <div style="background:#064e3b;border:1px solid #10b981;color:#a7f3d0;padding:12px;border-radius:6px;margin-bottom:12px">
                    {{ session('status') }}
                </div>
            @endif
            @if($notifications->isEmpty())
                <div class="muted">No messages yet.</div>
            @else
                @foreach($notifications as $notification)
                    <div class="message {{ $notification->read_at ? '' : 'unread' }}">
                        <div class="message-header">
                            <span class="message-from">
                                @if($notification->type === 'App\Notifications\OrderStatusUpdateNotification')
                                    📦 Order Status Update
                                @elseif($notification->type === 'App\Notifications\OrderUnavailableNotification')
                                    ⚠️ Order Unavailable
                                @elseif($notification->type === 'App\Notifications\AdminMessage')
                                    💬 From: {{ $notification->data['admin_name'] ?? 'Admin' }}
                                @else
                                    🔔 System Notification
                                @endif
                            </span>
                            <span class="message-date">{{ $notification->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div class="message-content">
                            @if($notification->type === 'App\Notifications\OrderStatusUpdateNotification')
                                <div style="margin-bottom: 8px;">
                                    <strong>Order #{{ $notification->data['order_number'] ?? 'N/A' }}</strong>
                                </div>
                                <div style="margin-bottom: 8px;">
                                    Status changed from <span style="color: #ffb4b4;">{{ $notification->data['old_status_label'] ?? 'Unknown' }}</span> 
                                    to <span style="color: #90ee90;">{{ $notification->data['new_status_label'] ?? 'Unknown' }}</span>
                                </div>
                                @if($notification->data['admin_notes'])
                                    <div style="background: rgba(231, 210, 184, 0.1); padding: 8px; border-radius: 4px; margin-top: 8px;">
                                        <strong>Admin Note:</strong> {{ $notification->data['admin_notes'] }}
                                    </div>
                                @endif
                            @elseif($notification->type === 'App\Notifications\OrderUnavailableNotification')
                                <div style="margin-bottom: 8px;">
                                    <strong>Order #{{ $notification->data['order_number'] ?? 'N/A' }}</strong>
                                </div>
                                <div style="color: #ffb4b4;">
                                    {{ $notification->data['message'] ?? 'Your order is currently unavailable.' }}
                                </div>
                            @else
                                {{ $notification->data['message'] ?? 'No message content' }}
                            @endif
                        </div>
                        @if(!$notification->read_at)
                            <div style="margin-top: 8px;">
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: #e7d2b8; color: #111; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                                        Mark as Read
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
                
                <div style="margin-top:16px">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

        <!-- Reply to admin -->
        <div class="card" style="margin-top: 1rem;">
            <h2 style="margin:0 0 8px">Send a message to admin</h2>
            <form method="POST" action="{{ route('messages.reply') }}">
                @csrf
                <textarea name="message" rows="4" style="width:100%;padding:12px;border-radius:8px;background:#1a1a1a;color:#e9e9e9;border:1px solid #2a2a2a" placeholder="Write your message..." required></textarea>
                <div style="margin-top:8px">
                    <button type="submit" class="btn" style="background:#e7d2b8;color:#111;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600">Send</button>
                </div>
            </form>
        </div>
        
        <p style="margin-top:16px;color:#9f9f9f">
            <a href="{{ route('account') }}">Back to account</a>
        </p>
    </div>
</body>
</html>
