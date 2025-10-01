@extends('layouts.app')

@section('title', 'Shopping Cart - L\'essence')

@section('styles')
<style>
    .container { max-width: 960px; margin: 0 auto; padding: 2rem; }
    .page-title { 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        font-weight: 600; 
        margin: 0 0 2rem;
        color: #e7d2b8;
        text-align: center;
    }
    .card { 
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #222; 
        border-radius: 12px; 
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .cart-table { 
        width: 100%; 
        border-collapse: collapse;
        margin-bottom: 2rem;
    }
    .cart-table th, .cart-table td { 
        padding: 1rem; 
        border-bottom: 1px solid #2a2a2a; 
        text-align: left;
    }
    .cart-table th { 
        color: #cfcfcf; 
        font-weight: 600;
        background: #1a1a1a;
    }
    .cart-table td {
        vertical-align: middle;
    }
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .product-name {
        font-weight: 600;
        color: #e9e9e9;
        font-size: 1.1rem;
    }
    .variant-name {
        color: #9f9f9f;
        font-size: 0.9rem;
    }
    .price {
        font-weight: 600;
        color: #e7d2b8;
        font-size: 1.1rem;
    }
    .quantity-input {
        width: 80px;
        padding: 0.5rem;
        border-radius: 6px;
        border: 1px solid #2a2a2a;
        background: #0f0f0f;
        color: #fff;
        text-align: center;
        font-size: 1rem;
    }
    .quantity-input:focus {
        outline: none;
        border-color: #e7d2b8;
        box-shadow: 0 0 0 2px rgba(231, 210, 184, 0.2);
    }
    .btn { 
        background: #e7d2b8; 
        color: #111; 
        padding: 0.75rem 1.5rem; 
        border-radius: 8px; 
        border: none; 
        font-weight: 600; 
        cursor: pointer; 
        text-decoration: none; 
        display: inline-block;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    .btn:hover {
        background: #d4c4a8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
    }
    .btn:active {
        transform: translateY(0);
    }
    .btn-danger { 
        background: #dc3545; 
        color: #fff;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    .btn-success {
        background: #25D366;
        color: #111;
    }
    .btn-primary {
        background: #1877f2;
        color: #fff;
    }
    .total { 
        font-size: 1.5rem; 
        font-weight: 700; 
        color: #e7d2b8; 
        margin: 1rem 0;
        text-align: right;
        padding: 1rem;
        background: #1a1a1a;
        border-radius: 8px;
    }
    .empty { 
        text-align: center; 
        color: #9f9f9f; 
        padding: 3rem;
    }
    .empty h3 {
        color: #e9e9e9;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }
    .checkout-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 2rem;
    }
    .admin-notice {
        color: #ffb4b4;
        background: #2a1a1a;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #4a2a2a;
        text-align: center;
    }
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .alert-success {
        background: #1a2a1a;
        color: #90ee90;
        border: 1px solid #2a4a2a;
    }
    .alert-error {
        background: #2a1a1a;
        color: #ffb4b4;
        border: 1px solid #4a2a2a;
    }
    @media (max-width: 768px) {
        .container { padding: 1rem; }
        .cart-table { font-size: 0.9rem; }
        .cart-table th, .cart-table td { padding: 0.5rem; }
        .checkout-actions { flex-direction: column; }
        .btn { width: 100%; text-align: center; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
    </div>
    
    <h1 class="page-title">Shopping Cart</h1>
    
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card">
        @if(!$cart || empty($cart->items))
            <div class="empty">
                <h3>Your cart is empty</h3>
                <p>Add some beautiful fragrances to get started!</p>
                <a href="{{ route('shop.index') }}" class="btn">Continue Shopping</a>
            </div>
        @else
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                    <tr>
                        <td>
                            <div class="product-info">
                                <div class="product-name">{{ $item['product_name'] }}</div>
                                <div class="variant-name">{{ $item['variant_name'] }}</div>
                            </div>
                        </td>
                        <td class="price">৳{{ number_format($item['price'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update') }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                <input type="number" name="quantity" value="{{ $item['qty'] }}" min="0" max="99" class="quantity-input" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="price">৳{{ number_format($item['price'] * $item['qty'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update') }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                <input type="hidden" name="quantity" value="0">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="total">
                Total: ৳{{ number_format($cart->grand_total, 2) }}
            </div>
            
            <!-- Payment Notice -->
            <div style="background: #1a1a2e; border: 1px solid #16213e; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color: #ffd700;">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <h4 style="margin: 0; color: #e7d2b8; font-size: 1rem;">Payment Information</h4>
                </div>
                <p style="margin: 0; color: #b0b0b0; font-size: 0.9rem; line-height: 1.4;">
                    <strong>Note:</strong> bKash, Nagad, Rocket, and other online payment facilities are currently not available. 
                    Please use WhatsApp or Facebook Messenger to place your order. Our team will guide you through the payment process.
                </p>
            </div>

            <div class="checkout-actions">
                @if(auth()->user()->role === 'admin')
                    <div class="admin-notice">Admins cannot place orders.</div>
                @else
                    <a href="{{ route('checkout.whatsapp') }}" class="btn btn-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        Order via WhatsApp
                    </a>
                    <a href="{{ route('checkout.facebook') }}" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Order via Facebook
                    </a>
                    <a href="{{ route('checkout') }}" class="btn">Cash on Delivery</a>
                @endif
                <form method="POST" action="{{ route('cart.clear') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Clear Cart</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
