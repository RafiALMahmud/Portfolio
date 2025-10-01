<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - L'essence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #e9e9e9; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        .header { background: rgba(10, 10, 10, 0.95); backdrop-filter: blur(10px); position: fixed; top: 0; left: 0; right: 0; z-index: 1000; border-bottom: 1px solid #1a1a1a; }
        .header-content { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #e7d2b8; }
        .nav { display: flex; gap: 2rem; align-items: center; }
        .nav a { color: #cfcfcf; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .nav a:hover { color: #e7d2b8; }
        .auth-links { display: flex; gap: 1rem; }
        .btn-primary { background: #e7d2b8; color: #111; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: none; cursor: pointer; font-size: 1rem; }
        .btn-primary:hover { background: #d4c4a8; transform: translateY(-1px); }
        .btn-outline { border: 1px solid #e7d2b8; color: #e7d2b8; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 500; transition: all 0.3s; background: transparent; cursor: pointer; font-size: 1rem; }
        .btn-outline:hover { background: #e7d2b8; color: #111; }
        
        /* Main Content */
        .main { padding: 6rem 0 4rem; min-height: 100vh; }
        .checkout-container { display: grid; grid-template-columns: 1fr 400px; gap: 3rem; max-width: 1000px; margin: 0 auto; }
        
        /* Order Summary */
        .order-summary { background: #141414; border: 1px solid #222; border-radius: 12px; padding: 2rem; height: fit-content; }
        .summary-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.5rem; color: #e7d2b8; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 0.75rem; padding-bottom: 0.75rem; border-bottom: 1px solid #333; }
        .summary-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .summary-label { color: #9f9f9f; }
        .summary-value { color: #e9e9e9; font-weight: 500; }
        .summary-total { font-size: 1.2rem; font-weight: 600; color: #e7d2b8; }
        
        /* Payment Methods */
        .payment-methods { background: #141414; border: 1px solid #222; border-radius: 12px; padding: 2rem; }
        .payment-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.5rem; color: #e7d2b8; }
        .payment-option { margin-bottom: 1rem; }
        .payment-btn { width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 1rem; border: 2px solid #333; border-radius: 8px; background: #1a1a1a; color: #e9e9e9; text-decoration: none; transition: all 0.3s; }
        .payment-btn:hover { border-color: #e7d2b8; background: #222; }
        .payment-icon { width: 24px; height: 24px; }
        
        /* Cart Items */
        .cart-items { margin-bottom: 2rem; }
        .cart-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #333; }
        .cart-item:last-child { border-bottom: none; }
        .item-image { width: 80px; height: 80px; background: #1a1a1a; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #666; }
        .item-details { flex: 1; }
        .item-title { font-weight: 600; margin-bottom: 0.25rem; color: #e9e9e9; }
        .item-variant { color: #9f9f9f; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .item-price { color: #e7d2b8; font-weight: 600; }
        .item-qty { color: #9f9f9f; font-size: 0.9rem; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .checkout-container { grid-template-columns: 1fr; gap: 2rem; }
            .main { padding: 5rem 0 2rem; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('home') }}" style="color: #e7d2b8; text-decoration: none;">L'essence</a>
                </div>
                <nav class="nav">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('shop.index') }}">Shop</a>
                    <a href="{{ route('cart.index') }}">Cart</a>
                </nav>
                <div class="auth-links">
                    <a href="{{ route('account') }}" class="btn-outline">Account</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-outline">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Navigation -->
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('cart.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Cart
                </a>
            </div>
            
            <div class="checkout-container">
                <!-- Order Summary -->
                <div class="order-summary">
                    <h2 class="summary-title">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="cart-items">
                        @foreach($cart->items ?? [] as $item)
                            <div class="cart-item">
                                <div class="item-image">
                                    @if(isset($item['image']) && $item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] ?? $item['title'] ?? 'Product' }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <div class="item-title">{{ $item['product_name'] ?? $item['title'] ?? 'Unknown Product' }}</div>
                                    <div class="item-variant">{{ $item['variant_name'] ?? $item['variant'] ?? '' }}</div>
                                    <div class="item-price">৳{{ number_format($item['price'] ?? 0, 2) }}</div>
                                    <div class="item-qty">Qty: {{ $item['qty'] ?? 1 }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Totals -->
                    <div class="summary-item">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Tax (10%)</span>
                        <span class="summary-value">৳{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Total</span>
                        <span>৳{{ number_format($total, 2) }}</span>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h2 class="payment-title">Payment Method</h2>
                    
                    <!-- Payment Notice -->
                    <div style="background: #1a1a2e; border: 1px solid #16213e; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color: #ffd700;">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <h4 style="margin: 0; color: #e7d2b8; font-size: 1rem;">Payment Information</h4>
                        </div>
                        <p style="margin: 0; color: #b0b0b0; font-size: 0.9rem; line-height: 1.4;">
                            <strong>Cash on Delivery Only:</strong> We currently accept only Cash on Delivery payments. 
                            You will pay when the product is delivered to your address.
                        </p>
                    </div>
                    
                    <div class="payment-option">
                        <div style="background: #25D366; border: 2px solid #25D366; border-radius: 8px; padding: 1.5rem; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="color: #fff;">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <h3 style="margin: 0; color: #fff; font-size: 1.2rem;">Cash on Delivery</h3>
                            </div>
                            <p style="margin: 0; color: #fff; font-size: 0.9rem; opacity: 0.9;">
                                Pay when your order is delivered. No advance payment required.
                            </p>
                        </div>
                    </div>
                    
                    <div class="payment-option">
                        <form method="POST" action="{{ route('checkout') }}" style="margin-top: 1rem;">
                            @csrf
                            <button type="submit" class="btn-primary" style="width: 100%; background: #25D366; border-color: #25D366; font-size: 1.1rem; padding: 1rem;">
                                Place Order (Cash on Delivery)
                            </button>
                        </form>
                    </div>
                    
                    <div style="margin-top: 1.5rem; padding: 1rem; background: #1a1a1a; border-radius: 8px; border: 1px solid #333;">
                        <p style="color: #9f9f9f; font-size: 0.9rem; margin: 0; text-align: center;">
                            📞 <strong>Note:</strong> After placing your order, our team will contact you to confirm the details and arrange delivery.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
