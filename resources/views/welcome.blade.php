@extends('layouts.app')
@section('title', "L'essence - Luxury Fragrances")
@section('styles')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Using global header/nav styles from layout */
        
        /* Hero Section */
        .hero { 
            padding: 8rem 0 4rem; 
            text-align: center; 
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a1a0a 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(231, 210, 184, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        .hero h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 4rem; 
            font-weight: 300; 
            margin-bottom: 1rem; 
            color: #e7d2b8; 
            letter-spacing: 0.3em;
            text-shadow: 0 0 20px rgba(231, 210, 184, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }
        @keyframes glow {
            from { text-shadow: 0 0 20px rgba(231, 210, 184, 0.3); }
            to { text-shadow: 0 0 30px rgba(231, 210, 184, 0.5), 0 0 40px rgba(231, 210, 184, 0.2); }
        }
        .hero-subtitle {
            font-size: 1.5rem;
            color: #e7d2b8;
            font-style: italic;
            margin-bottom: 0.5rem;
            font-weight: 300;
        }
        .hero-description {
            font-size: 1.1rem; 
            color: #9f9f9f; 
            max-width: 600px; 
            margin: 0 auto 2rem;
            font-weight: 300;
            line-height: 1.7;
        }
        .hero-cta { display: flex; gap: 1.5rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
        .hero-cta .btn-primary {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .hero-cta .btn-outline {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        
        /* Featured Products */
        .featured { padding: 4rem 0; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; text-align: center; margin-bottom: 3rem; color: #e7d2b8; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem; }
        
        /* Featured Products Slider (fade, responsive, no image stretch) */
        .fp-slider {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: #0f0f0f;
            border: 1px solid #1a1a1a;
            height: 400px; /* fixed desktop height */
        }
        .fp-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.6s ease;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem;
            align-items: center;
        }
        .fp-slide.active { opacity: 1; }
        .fp-image-wrap {
            width: 100%;
            height: 100%;
            background: #141414;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #222;
        }
        .fp-image {
            width: 100%;
            height: 100%;
            object-fit: cover; /* maintain aspect ratio, fill without stretching */
            object-position: center;
        }
        .fp-info { padding: 0.5rem; }
        .fp-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #e7d2b8; margin-bottom: 0.5rem; }
        .fp-desc { color: #b0b0b0; margin-bottom: 1rem; line-height: 1.6; }
        .fp-actions { display: flex; gap: 0.75rem; }
        .fp-btn { padding: 0.75rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .fp-btn-primary { background: #e7d2b8; color: #111; }
        .fp-btn-outline { border: 1px solid #e7d2b8; color: #e7d2b8; }
        .fp-prev, .fp-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.4);
            border: 1px solid #2a2a2a;
            color: #e9e9e9;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }
        .fp-prev { left: 12px; }
        .fp-next { right: 12px; }
        .fp-dots { text-align: center; padding: 1rem; }
        .fp-dot { width: 10px; height: 10px; background: #555; border-radius: 50%; display: inline-block; margin: 0 6px; cursor: pointer; transition: background 0.3s; }
        .fp-dot.active { background: #e7d2b8; }
        /* Responsive heights and layout */
        @media (max-width: 768px) {
            .fp-slider { height: 250px; }
            .fp-slide { grid-template-columns: 1fr; gap: 1rem; padding: 1rem; }
        }
        
        .product-card { 
            background: #141414; 
            border: 1px solid #222; 
            border-radius: 12px; 
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
        .product-card:hover .btn-outline {
            background: #e7d2b8;
            color: #111;
        }
        .product-image { 
            width: 75%; 
            height:220px; 
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #666; 
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }
        .product-image:hover img {
            transform: scale(1.08);
        }
        .product-info { padding: 1.5rem; }
        .product-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem; color: #e9e9e9; }
        .product-price { font-size: 1.1rem; color: #e7d2b8; font-weight: 600; }
        
        /* Footer */
        .footer { background: #0a0a0a; border-top: 1px solid #1a1a1a; padding: 3rem 0; text-align: center; color: #9f9f9f; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            /* header elements are managed by layout */
            .hero-cta { flex-direction: column; align-items: center; }
            .products-grid { 
                grid-template-columns: 1fr; 
                gap: 1.5rem; 
            }
            .feature-callouts {
                padding: 1rem !important;
            }
            .feature-item {
                margin-bottom: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }
            .slideshow-slide {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }
            .slideshow-controls {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .slideshow-btn {
                padding: 10px 16px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero h1 { font-size: 2rem; }
            .hero-subtitle { font-size: 1.2rem; }
            .hero-description { font-size: 1rem; }
            .header-content {
                padding: 0.5rem 10px;
                min-height: 50px;
            }
            .auth-links {
                gap: 0.25rem;
            }
            .btn-outline, .btn-primary {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }
            .cart-link {
                width: 32px;
                height: 32px;
                margin-right: 4px;
            }
            .cart-link svg {
                width: 16px;
                height: 16px;
            }
            .cart-badge {
                font-size: 10px;
                padding: 1px 4px;
                min-width: 14px;
                top: -4px;
                right: -8px;
            }
            .feature-callouts {
                padding: 0.75rem !important;
            }
            .slideshow-slide {
                padding: 1rem;
            }
            .slideshow-controls {
                flex-direction: column;
                gap: 1rem;
            }
            .slideshow-dots {
                order: -1;
            }
        }

        /* Contact */
        .contact { padding: 4rem 0; }
        .contact-title { font-family: 'Playfair Display', serif; font-size: 3rem; text-align: center; margin-bottom: 2rem; color: #1f2a35; letter-spacing: 2px; text-transform: uppercase; }
        .contact-card { background:#2a2a2a; border:1px solid #3a3a3a; border-radius:16px; padding:24px; max-width:720px; margin:0 auto; }
        .contact-field { 
            width:100%; 
            padding:16px; 
            margin-bottom:16px; 
            border-radius:10px; 
            border:1px solid #555; 
            background:#444; 
            color:#e9e9e9; 
            font-size:16px; 
            transition: all 0.3s ease;
        }
        .contact-field:focus {
            outline: none;
            border-color: #d1ad33;
            background: #555;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(209, 173, 51, 0.2);
        }
        .contact-field:hover {
            border-color: #777;
            background: #4a4a4a;
        }
        .contact-field::placeholder { color:#b0b0b0; }
        .contact-textarea { min-height:200px; resize:vertical; }
        .contact-btn { 
            width:100%; 
            background:#d1ad33; 
            color:#111; 
            font-weight:700; 
            padding:16px; 
            border:none; 
            border-radius:28px; 
            cursor:pointer; 
            font-size:18px; 
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .contact-btn:hover { 
            background:#c39c22; 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(209, 173, 51, 0.4);
        }
        .contact-btn:active {
            transform: translateY(0);
        }

        /* (Removed old generic slideshow styles; using .fp-* now) */

        .product-details {
            padding: 0 1rem;
        }

        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #e7d2b8;
            font-weight: 600;
        }

        .product-description {
            font-size: 1rem;
            color: #b0b0b0;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .product-price {
            margin-bottom: 2rem;
            position: relative;
        }

        .original-price {
            text-decoration: line-through;
            color: #888;
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .discounted-price {
            color: #e7d2b8;
            font-size: 1.8rem;
            font-weight: bold;
            margin-right: 1rem;
        }

        .price {
            color: #e7d2b8;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .price-unavailable {
            color: #888;
            font-style: italic;
            font-size: 1.2rem;
        }

        .discount-badge {
            background: #ff4444;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            position: absolute;
            top: -10px;
            right: 0;
            animation: pulse 2s infinite;
        }

        @keyframes fade {
            from { opacity: 0.4; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .dots {
            text-align: center;
            padding: 1rem;
        }

        .dot {
            cursor: pointer;
            height: 12px;
            width: 12px;
            margin: 0 6px;
            background-color: #555;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .dot:hover {
            background-color: #888;
        }

        .dot.active-dot {
            background-color: #e7d2b8;
        }

        .no-products {
            text-align: center;
            color: #9f9f9f;
            padding: 3rem;
        }

        .no-products p {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .view-all-section {
            text-align: center;
            margin-top: 3rem;
        }

        .view-all-section .btn-outline {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .slideshow-container {
                margin: 1rem auto;
                border-radius: 10px;
            }

            .slide {
                padding: 1.5rem;
            }

            .product-image img {
                height: 250px;
            }

            .no-image-placeholder {
                height: 250px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .discounted-price,
            .price {
                font-size: 1.5rem;
            }

            .original-price {
                font-size: 1rem;
            }
        }
        </style>
@endsection

@section('content')

    <!-- Hero Section -->
    <section class="hero" id="home">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="{{ asset('storage/intro.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay"></div>
        <div class="container">
            <h1>L'essence</h1>
            <p class="hero-subtitle">Your ambrosial essence</p>
            <p class="hero-description">Discover the world's most exquisite fragrances, crafted with passion and precision.</p>
            
            <div class="hero-cta">
                <a href="{{ route('shop.index') }}" class="btn-primary">Explore Fragrances</a>
                <a href="#contact" class="btn-outline">Bespoke Service</a>
            </div>
        </div>
    </section>

    <!-- Featured Fragrances (Responsive Slider) -->
    <section class="featured" id="shop">
        <div class="container">
            <h2 class="section-title">Featured Fragrances</h2>
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                <div class="fp-slider" id="fpSlider">
                    @foreach($featuredProducts as $index => $product)
                        <div class="fp-slide{{ $index === 0 ? ' active' : '' }}">
                            <div class="fp-image-wrap">
                                @if($product->main_image)
                                    <img class="fp-image" src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name ?? $product->title }}">
                                @else
                                    <img class="fp-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 400'%3E%3Crect width='600' height='400' fill='%232a2a2a'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%23666' font-size='24'%3ENo Image%3C/text%3E%3C/svg%3E" alt="No Image">
                                @endif
                            </div>
                            <div class="fp-info">
                                <h3 class="fp-title">{{ $product->product_name ?? $product->title }}</h3>
                                <p class="fp-desc">{{ Str::limit($product->story, 140) }}</p>
                                <div style="margin: 0.5rem 0 1rem; font-weight: 700;">
                                    @if($product->variants->count() > 0)
                                        @php
                                            $min = $product->variants->min('price');
                                            $max = $product->variants->max('price');
                                        @endphp
                                        @if($product->hasDiscount())
                                            <div style="color: #888; font-weight: 500;">
                                                <span style="text-decoration: line-through;">৳{{ number_format($min, 2) }}</span>
                                                @if($product->variants->count() > 1)
                                                    - <span style="text-decoration: line-through;">৳{{ number_format($max, 2) }}</span>
                                                @endif
                                            </div>
                                            <div style="color: #e7d2b8;">
                                                From ৳{{ $product->getFormattedDiscountedPrice($min) }}
                                                @if($product->variants->count() > 1)
                                                    - ৳{{ $product->getFormattedDiscountedPrice($max) }}
                                                @endif
                                                <span style="background:#ff4444; color:#fff; padding:2px 8px; border-radius:12px; font-size:0.8rem; margin-left:8px;">{{ $product->discount_percentage }}% OFF</span>
                                            </div>
                                        @else
                                            <div style="color: #e7d2b8;">
                                                From ৳{{ number_format($min, 2) }}
                                                @if($product->variants->count() > 1)
                                                    - ৳{{ number_format($max, 2) }}
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div style="color:#888; font-weight:500;">Price not available</div>
                                    @endif
                                </div>
                                <div class="fp-actions">
                                    <a href="{{ route('product.show', $product->slug) }}" class="fp-btn fp-btn-primary">View Details</a>
                                    <a href="{{ route('shop.index') }}" class="fp-btn fp-btn-outline">Shop All</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button class="fp-prev" id="fpPrev" aria-label="Previous">‹</button>
                    <button class="fp-next" id="fpNext" aria-label="Next">›</button>
                </div>
                <div class="fp-dots" id="fpDots"></div>
            @else
                <div class="no-products">
                    <p>No fragrances available at the moment.</p>
                    <p>Check back soon for our latest collection.</p>
                </div>
            @endif
            <div class="view-all-section">
                <a href="{{ route('shop.index') }}" class="btn-outline">View All Fragrances</a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about" style="padding: 4rem 0; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); border-top: 1px solid #1a1a1a;">
        <div class="container">
            <h2 class="section-title" style="margin-bottom: 3rem;">Our Story</h2>
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <div style="background: linear-gradient(135deg, rgba(231, 210, 184, 0.05), rgba(231, 210, 184, 0.1)); border-radius: 16px; padding: 3rem; border: 1px solid rgba(231, 210, 184, 0.2);">
                    <p style="font-size: 1.2rem; line-height: 1.8; color: #cfcfcf; margin-bottom: 2rem; font-style: italic;">
                        "In 2025, two friends with a shared passion for the art of fragrance embarked on a journey to create something extraordinary. What started as late-night conversations about the perfect scent has evolved into L'essence – our humble attempt to capture the essence of luxury and elegance in every bottle."
                    </p>
                    <p style="font-size: 1rem; line-height: 1.7; color: #9f9f9f; margin-bottom: 1.5rem;">
                        We believe that fragrance is more than just a scent – it's a memory, an emotion, a story waiting to be told. Every creation in our collection is crafted with meticulous attention to detail, using only the finest ingredients sourced from around the world.
                    </p>
                    <p style="font-size: 1rem; line-height: 1.7; color: #9f9f9f;">
                        As we continue to grow and learn, our commitment remains the same: to bring you fragrances that not only smell incredible but also tell your unique story. Join us on this journey as we strive to do better, create more, and share the beauty of scent with the world.
                    </p>
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(231, 210, 184, 0.2);">
                        <p style="color: #e7d2b8; font-weight: 600; font-size: 0.9rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            With love and dedication,<br>
                            The L'essence Team
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact" style="border-top: 1px solid #1a1a1a;">
        <div class="container">
            <h2 class="contact-title">Contact Us</h2>
            <div class="contact-card">
                <form action="https://formspree.io/f/manpqvov" method="POST">
                    <input class="contact-field" type="text" name="name" placeholder="Name" value="{{ auth()->check() && auth()->user()->role === 'user' ? auth()->user()->name : '' }}" required>
                    <input class="contact-field" type="email" name="email" placeholder="Email" value="{{ auth()->check() && auth()->user()->role === 'user' ? auth()->user()->email : '' }}" required>
                    <textarea class="contact-field contact-textarea" name="message" placeholder="Message" required></textarea>
                    <button class="contact-btn" type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        // Featured products slider
        (function() {
            const slider = document.getElementById('fpSlider');
            if (!slider) return;
            const slides = slider.querySelectorAll('.fp-slide');
            const prev = document.getElementById('fpPrev');
            const next = document.getElementById('fpNext');
            const dotsContainer = document.getElementById('fpDots');
            let index = 0;
            let timerId;

            // Build dots
            slides.forEach((_, i) => {
            const dot = document.createElement('span');
                dot.className = 'fp-dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => { goTo(i); resetTimer(); });
            dotsContainer.appendChild(dot);
        });
            const dots = dotsContainer.querySelectorAll('.fp-dot');

            function show(i) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                index = (i + slides.length) % slides.length;
                slides[index].classList.add('active');
                dots[index].classList.add('active');
            }
            function nextSlide() { show(index + 1); }
            function prevSlide() { show(index - 1); }
            function goTo(i) { show(i); }

        function resetTimer() {
                clearInterval(timerId);
                timerId = setInterval(nextSlide, 3000);
            }
            // Controls
            if (next) next.addEventListener('click', () => { nextSlide(); resetTimer(); });
            if (prev) prev.addEventListener('click', () => { prevSlide(); resetTimer(); });

            // Start autoplay
            resetTimer();
        })();

        // Video autoplay handling
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('.hero-video');
            if (video) {
                // Ensure video plays
                video.play().catch(function(error) {
                    console.log('Video autoplay failed:', error);
                    // Fallback: try to play when user interacts
                    document.addEventListener('click', function() {
                        video.play().catch(function(e) {
                            console.log('Video play failed:', e);
                        });
                    }, { once: true });
                });

                // Handle video loading
                video.addEventListener('loadeddata', function() {
                    console.log('Video loaded successfully');
                });

                video.addEventListener('error', function(e) {
                    console.log('Video error:', e);
                });
            }
        });
    </script>
@endsection
