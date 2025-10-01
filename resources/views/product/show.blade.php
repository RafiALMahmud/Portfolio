@extends('layouts.app')

@section('title', ($product->product_name ?? $product->title) . ' - L\'essence')

@section('styles')
<style>
    .container { max-width: 1100px; margin: 0 auto; padding: 2rem; }
    .product-wrap { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 2rem; 
        margin-bottom: 2rem;
    }
    .product-image { 
        width: 100%; 
        height: 480px; 
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        display: flex; 
        align-items: center; 
        justify-content: center;
        border-radius: 12px; 
        border: 1px solid #222; 
        overflow: hidden;
        position: relative;
    }
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-image:hover img {
        transform: scale(1.05);
    }
    .product-info { 
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #222; 
        border-radius: 12px; 
        padding: 2rem;
    }
    .product-title { 
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem; 
        font-weight: 700; 
        margin: 0 0 1rem;
        color: #e7d2b8;
    }
    .product-story { 
        margin: 1rem 0; 
        color: #cfcfcf; 
        line-height: 1.6;
        font-size: 1.1rem;
    }
    .discount-badge {
        display: inline-block;
        background: #e7d2b8;
        color: #111;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cfcfcf;
        font-weight: 500;
    }
    .form-select, .form-input {
        width: 100%;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
        background: #0f0f0f;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #e7d2b8;
        box-shadow: 0 0 0 2px rgba(231, 210, 184, 0.2);
    }
    .btn-add-cart {
        background: linear-gradient(45deg, #e7d2b8, #d4af37);
        color: #111;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-add-cart:hover {
        background: linear-gradient(45deg, #d4af37, #e7d2b8);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(231, 210, 184, 0.3);
    }
    .variants-list {
        margin: 1rem 0;
    }
    .variant-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        margin: 0.5rem 0;
        background: #1a1a1a;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
    }
    .variant-name {
        font-weight: 500;
        color: #e9e9e9;
    }
    .variant-price {
        font-weight: 600;
        color: #e7d2b8;
    }
    .variant-stock {
        font-size: 0.9rem;
        color: #9f9f9f;
    }
    @media (max-width: 768px) {
        .product-wrap { 
            grid-template-columns: 1fr; 
            gap: 1.5rem; 
        }
        .product-image { height: 300px; }
        .product-title { font-size: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div style="margin-top: 1rem;">
        <a href="{{ route('shop.index') }}" class="back-button">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to Shop
        </a>
    </div>
    
    <div class="product-wrap">
        <div class="product-image">
            @if($product->main_image)
                <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->product_name ?? $product->title }}">
            @else
                <div style="color: #666; font-size: 1.2rem;">No Image Available</div>
            @endif
        </div>
        <div class="product-info">
            <h1 class="product-title">{{ $product->product_name ?? $product->title }}</h1>
            @if($product->discount_percentage)
                <div class="discount-badge">Discount: {{ (float)$product->discount_percentage }}%</div>
            @endif
            <div class="product-story">{{ $product->story }}</div>

            @if($product->variants->count())
                @php $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin'; @endphp
                @unless($isAdmin)
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Select Size & Concentration</label>
                        <select name="variant_id" class="form-select" required>
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}">
                                    {{ $variant->name ?? 'Option' }} - 
                                    @if($product->hasDiscount())
                                        <span style="text-decoration: line-through; color: #888;">৳{{ number_format($variant->price, 2) }}</span>
                                        <span style="color: #e7d2b8; font-weight: bold;">৳{{ $product->getFormattedDiscountedPrice($variant->price) }}</span>
                                    @else
                                        ৳{{ number_format($variant->price, 2) }}
                                    @endif
                                    @if($variant->stock <= 5)
                                        (Only {{ $variant->stock }} left)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" max="10" class="form-input" required>
                    </div>
                    <button class="btn-add-cart" type="submit">Add to Cart</button>
                </form>
                @else
                <div style="margin: 1rem 0; color: #9f9f9f; font-size: 0.95rem;">
                    Admins can view products but cannot add items to cart.
                </div>
                @endunless
                
                <div class="variants-list">
                    <h3 style="color: #e7d2b8; margin-bottom: 1rem;">Available Options:</h3>
                    @foreach($product->variants as $variant)
                        <div class="variant-item">
                            <div>
                                <div class="variant-name">{{ $variant->name ?? 'Option' }}</div>
                                <div class="variant-stock">
                                    @if($variant->stock > 5)
                                        In Stock
                                    @elseif($variant->stock > 0)
                                        Only {{ $variant->stock }} left
                                    @else
                                        Out of Stock
                        @endif
                                </div>
                            </div>
                            <div class="variant-price">
                                @if($product->hasDiscount())
                                    <span style="text-decoration: line-through; color: #888; margin-right: 0.5rem;">৳{{ number_format($variant->price, 2) }}</span>
                                    <span style="color: #e7d2b8; font-weight: bold;">৳{{ $product->getFormattedDiscountedPrice($variant->price) }}</span>
                                @else
                                    ৳{{ number_format($variant->price, 2) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="color: #9f9f9f; text-align: center; padding: 2rem;">
                    <p>This product is currently unavailable.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



