@extends('layouts.app')

@section('title', 'Shop - L\'essence')

@section('styles')
<style>
    .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
    .header { text-align: center; margin-bottom: 3rem; }
    .header h1 { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.5rem; 
        font-weight: 700; 
        margin-bottom: 1rem;
        background: linear-gradient(45deg, #e7d2b8, #d4af37);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .header p { font-size: 1.1rem; color: #b0b0b0; max-width: 600px; margin: 0 auto; }
    
    /* Search and Filter Section */
    .search-filter-section {
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .search-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 12px 16px;
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 8px;
        color: #e9e9e9;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #e7d2b8;
        box-shadow: 0 0 0 2px rgba(231, 210, 184, 0.2);
    }
    
    .search-input::placeholder {
        color: #666;
    }
    
    .filter-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 16px;
        background: transparent;
        border: 1px solid #2a2a2a;
        border-radius: 6px;
        color: #cfcfcf;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-btn:hover {
        border-color: #e7d2b8;
        color: #e7d2b8;
        background: rgba(231, 210, 184, 0.1);
    }
    
    .filter-btn.active {
        background: #e7d2b8;
        color: #111;
        border-color: #e7d2b8;
    }
    
    .filter-btn svg {
        width: 16px;
        height: 16px;
    }
    .products-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
        gap: 1.5rem; 
        margin-bottom: 3rem;
    }
    .product-card { 
        background: linear-gradient(145deg, #141414, #1a1a1a);
        border: 1px solid #2a2a2a;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        border-color: #e7d2b8;
    }
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    .product-card:hover .btn {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
    }
    .product-image { 
        width: 100%; 
        height: 200px; 
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        display: flex; 
        align-items: center; 
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    .product-info { padding: 1rem; }
    .product-title { 
        font-size: 1.1rem; 
        font-weight: 600; 
        margin-bottom: 0.5rem;
        color: #ffffff;
    }
    .product-story {
        font-size: 0.8rem;
        color: #b0b0b0;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-price { 
        font-size: 1.2rem; 
        font-weight: 700; 
        color: #e7d2b8;
        margin-bottom: 0.75rem;
    }
    .product-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .btn { 
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .btn-primary { 
        background: linear-gradient(45deg, #e7d2b8, #d4af37);
        color: #111;
        flex: 1;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #d4af37, #e7d2b8);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(231, 210, 184, 0.3);
    }
    .btn-secondary {
        background: transparent;
        color: #e7d2b8;
        border: 1px solid #e7d2b8;
    }
    .btn-secondary:hover {
        background: #e7d2b8;
        color: #111;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
    }
    .btn:active {
        transform: translateY(0);
    }
    .pagination { 
        display: flex; 
        justify-content: center; 
        margin-top: 3rem;
    }
    .pagination a, .pagination span {
        display: inline-block;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        background: #1a1a1a;
        color: #e9e9e9;
        text-decoration: none;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
        transition: all 0.3s ease;
    }
    .pagination a:hover {
        background: #e7d2b8;
        color: #111;
        border-color: #e7d2b8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
    }
    .pagination a:active {
        transform: translateY(0);
    }
    .pagination .current {
        background: #e7d2b8;
        color: #111;
        border-color: #e7d2b8;
    }
    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        color: #b0b0b0;
    }
    .no-products h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #e9e9e9;
    }
    
    /* Back Button Styles */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #e7d2b8;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 12px 20px;
        border-radius: 8px;
        position: relative;
        background: rgba(231, 210, 184, 0.1);
        border: 1px solid rgba(231, 210, 184, 0.2);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        z-index: 10;
    }
    
    .back-button:hover {
        background: rgba(231, 210, 184, 0.2);
        border-color: rgba(231, 210, 184, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 210, 184, 0.2);
        color: #d4c4a8;
    }
    
    .back-button:active {
        transform: translateY(0);
    }
    
    .back-button svg {
        transition: transform 0.3s ease;
    }
    
    .back-button:hover svg {
        transform: translateX(-2px);
    }
    @media (max-width: 768px) {
        .container { padding: 1rem; }
        .header h1 { font-size: 2rem; }
        .products-grid { 
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
            gap: 1.25rem; 
        }
        .product-actions { flex-direction: column; }
        .btn { width: 100%; text-align: center; }
        .back-button {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
        .feature-callouts {
            padding: 1rem !important;
        }
        .feature-item {
            margin-bottom: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        /* Search and Filter Responsive */
        .search-container {
            flex-direction: column;
        }
        .search-input {
            min-width: 100%;
        }
        .filter-buttons {
            justify-content: center;
        }
        .filter-btn {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
    }
    
    @media (max-width: 480px) {
        .products-grid { 
            grid-template-columns: 1fr; 
            gap: 1rem; 
        }
        .feature-callouts {
            padding: 0.75rem !important;
        }
        .product-info {
            padding: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterProducts(searchTerm, getActiveFilter());
    });
    
    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            const searchTerm = searchInput.value.toLowerCase();
            filterProducts(searchTerm, filter);
        });
    });
    
    function getActiveFilter() {
        const activeButton = document.querySelector('.filter-btn.active');
        return activeButton ? activeButton.getAttribute('data-filter') : 'all';
    }
    
    function filterProducts(searchTerm, filter) {
        productCards.forEach(card => {
            const productName = card.querySelector('.product-title').textContent.toLowerCase();
            const productStory = card.querySelector('.product-story') ? card.querySelector('.product-story').textContent.toLowerCase() : '';
            const productNotes = card.querySelector('.feature-callouts') ? card.querySelector('.feature-callouts').textContent.toLowerCase() : '';

            const matchesSearch = searchTerm === '' || 
                productName.includes(searchTerm) || 
                productStory.includes(searchTerm) ||
                productNotes.includes(searchTerm);

            // Price filter using data attributes
            let matchesPrice = true;
            const minPriceAttr = card.getAttribute('data-min-price');
            const minPrice = minPriceAttr ? parseFloat(minPriceAttr) : null;
            if (filter === 'price-low' && minPrice !== null) {
                matchesPrice = minPrice < 200;
            } else if (filter === 'price-mid' && minPrice !== null) {
                matchesPrice = minPrice >= 200 && minPrice <= 400;
            } else if (filter === 'price-high' && minPrice !== null) {
                matchesPrice = minPrice > 400;
            }

            // Discount filter using data attributes
            let matchesDiscount = true;
            if (filter === 'discount') {
                matchesDiscount = card.getAttribute('data-discount') === 'true';
            }

            if (matchesSearch && matchesPrice && matchesDiscount) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.3s ease-in';
            } else {
                card.style.display = 'none';
            }
        });

        updateResultsCount();
    }
    
    function updateResultsCount() {
        const visibleCards = document.querySelectorAll('.product-card[style*="display: block"], .product-card:not([style*="display: none"])');
        const totalCards = document.querySelectorAll('.product-card');
        
        // You can add a results counter here if needed
        console.log(`Showing ${visibleCards.length} of ${totalCards.length} products`);
    }
    
    // Initialize with "All Products" active
    document.querySelector('[data-filter="all"]').classList.add('active');
});

// Add fade-in animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>
@endsection

@section('content')
<div class="container">
    <div style="margin-top: 1rem;">
        <a href="{{ route('home') }}" class="back-button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
    </div>
    
    <div class="header">
        <h1>Our Collection</h1>
        <p>Discover our exquisite range of luxury fragrances, carefully crafted to capture the essence of elegance and sophistication.</p>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="search-filter-section">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search fragrances..." id="searchInput">
        </div>
        <div class="filter-buttons">
            <button class="filter-btn" data-filter="all">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18"/>
                </svg>
                All Products
            </button>
            <button class="filter-btn" data-filter="price-low">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Under ৳200
            </button>
            <button class="filter-btn" data-filter="price-mid">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                ৳200 - ৳400
            </button>
            <button class="filter-btn" data-filter="price-high">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Over ৳400
            </button>
            <button class="filter-btn" data-filter="discount">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                On Sale
            </button>
        </div>
    </div>
    
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            @php
                $minPrice = $product->variants->count() ? (float) $product->variants->min('price') : null;
                $maxPrice = $product->variants->count() ? (float) $product->variants->max('price') : null;
                $isDiscounted = method_exists($product, 'hasDiscount') ? (bool) $product->hasDiscount() : false;
            @endphp
            <div class="product-card" 
                 data-min-price="{{ $minPrice !== null ? number_format($minPrice, 2, '.', '') : '' }}"
                 data-max-price="{{ $maxPrice !== null ? number_format($maxPrice, 2, '.', '') : '' }}"
                 data-discount="{{ $isDiscounted ? 'true' : 'false' }}">
                <div class="product-image">
                    @if($product->main_image)
                        <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->product_name ?? $product->title }}">
                    @else
                        <div style="color: #666; font-size: 0.9rem;">No Image Available</div>
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-title">{{ $product->product_name ?? $product->title }}</div>
                    
                    <!-- Enhanced Feature Callouts -->
                    <div class="feature-callouts" style="margin: 0.75rem 0; padding: 0.75rem; background: linear-gradient(135deg, rgba(231, 210, 184, 0.08), rgba(231, 210, 184, 0.12)); border-radius: 8px; border: 1px solid rgba(231, 210, 184, 0.25); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <!-- Product Name -->
                        <div class="feature-item" style="margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(231, 210, 184, 0.2);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 3px; height: 3px; background: #e7d2b8; border-radius: 50%;"></div>
                                <strong style="color: #e7d2b8; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Product Name</strong>
                            </div>
                            <div style="color: #ffffff; font-weight: 600; margin-top: 0.25rem; font-size: 0.9rem;">{{ $product->product_name ?? $product->title }}</div>
                        </div>
                        
                        <!-- Perfume Notes -->
                        <div class="feature-item" style="margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(231, 210, 184, 0.2);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 3px; height: 3px; background: #e7d2b8; border-radius: 50%;"></div>
                                <strong style="color: #e7d2b8; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Fragrance Notes</strong>
                            </div>
                            <div style="color: #cfcfcf; margin-top: 0.25rem; font-size: 0.8rem; line-height: 1.3;">
                                @if($product->perfumeNotes->count() > 0)
                                    {{ $product->perfumeNotes->pluck('note_name')->join(', ') }}
                                @else
                                    <span style="color: #888; font-style: italic;">Notes not specified</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="feature-item" style="margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(231, 210, 184, 0.2);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 3px; height: 3px; background: #e7d2b8; border-radius: 50%;"></div>
                                <strong style="color: #e7d2b8; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Price Range</strong>
                            </div>
                            <div style="color: #ffffff; margin-top: 0.25rem; font-size: 0.85rem; font-weight: 600;">
                                @if($product->variants->count() > 0)
                                    @if($isDiscounted)
                                        <div style="margin-bottom: 0.25rem;">
                                            <span style="text-decoration: line-through; color: #888; font-size: 0.9rem;">
                                                From ৳{{ number_format($minPrice, 2) }}
                                                @if($product->variants->count() > 1)
                                                    - ৳{{ number_format($maxPrice, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                        <div style="color: #e7d2b8; font-weight: bold;">
                                            From ৳{{ $product->getFormattedDiscountedPrice($minPrice) }}
                                            @if($product->variants->count() > 1)
                                                - ৳{{ $product->getFormattedDiscountedPrice($maxPrice) }}
                                            @endif
                                        </div>
                                    @else
                                        From ৳{{ number_format($minPrice, 2) }}
                                        @if($product->variants->count() > 1)
                                            - ৳{{ number_format($maxPrice, 2) }}
                                        @endif
                                    @endif
                                @else
                                    <span style="color: #888; font-style: italic;">Price not available</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Available Sizes (Fixed) -->
                        <div class="feature-item">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 3px; height: 3px; background: #e7d2b8; border-radius: 50%;"></div>
                                <strong style="color: #e7d2b8; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Available Sizes</strong>
                            </div>
                            <div style="color: #cfcfcf; margin-top: 0.25rem; font-size: 0.8rem; font-weight: 500;">6 mL, 15 mL, 30 mL</div>
                        </div>
                    </div>
                    
                    @if($product->story)
                        <div class="product-story">{{ $product->story }}</div>
                    @endif
                    
                    @if($product->variants->count())
                        @php
                            $totalStock = $product->variants->sum('stock');
                        @endphp
                        @if($totalStock > 0)
                            <div class="product-stock" style="color: #90ee90; font-size: 0.9rem; margin-top: 0.5rem;">
                                @if($totalStock <= 5)
                                    ⚠️ Only {{ $totalStock }} left in stock
                                @else
                                    ✓ In Stock
                                @endif
                            </div>
                        @else
                            <div class="product-stock" style="color: #ff6b6b; font-size: 0.9rem; margin-top: 0.5rem;">
                                ❌ Out of Stock
                            </div>
                        @endif
                    @endif
                    
                    <div class="product-actions">
                        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary">View Details</a>
                        @if($product->variants->count())
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-secondary">Add to Cart</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $products->links() }}
        </div>
    @else
        <div class="no-products">
            <h3>No Products Available</h3>
            <p>We're currently updating our collection. Please check back soon for our latest fragrances.</p>
        </div>
    @endif
</div>
@endsection



