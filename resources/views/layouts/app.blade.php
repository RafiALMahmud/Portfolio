<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'L\'essence - Luxury Fragrances')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0b0b0b 0%, #1a1a1a 100%);
            color: #e9e9e9;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Header */
        .header { 
            background: rgba(10, 10, 10, 0.95); 
            backdrop-filter: blur(10px); 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            z-index: 1000; 
            border-bottom: 1px solid #1a1a1a; 
        }
        .header-content { 
            max-width: 1200px; 
            margin: 0 auto; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 1rem 20px; 
            min-height: 70px;
        }
        .logo { 
            font-family: 'Playfair Display', serif; 
            font-size: 1.5rem; 
            font-weight: 700; 
            color: #e7d2b8; 
            text-decoration: none;
        }
        .nav { 
            display: flex; 
            gap: 2rem; 
            align-items: center; 
            flex: 1;
            justify-content: center;
        }
        .nav a { 
            color: #cfcfcf; 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 6px;
            position: relative;
        }
        .nav a.active {
            font-weight: 600;
            color: #e7d2b8;
        }
        .nav a.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e7d2b8;
        }
        .nav a:hover { 
            color: #e7d2b8; 
            background: rgba(231, 210, 184, 0.1);
            transform: translateY(-2px);
        }
        .nav a:active {
            transform: translateY(0);
        }
        .auth-links { 
            display: flex; 
            gap: 1rem; 
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-primary { 
            background: #e7d2b8; 
            color: #111; 
            padding: 0.5rem 1rem; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s ease; 
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover { 
            background: #d4c4a8; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .btn-outline { 
            border: 1px solid #e7d2b8; 
            color: #e7d2b8; 
            padding: 0.5rem 1rem; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.3s ease; 
            background: transparent;
            position: relative;
            overflow: hidden;
        }
        .btn-outline:hover { 
            background: #e7d2b8; 
            color: #111;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 210, 184, 0.2);
        }
        .btn-outline:active {
            transform: translateY(0);
        }
        .btn-secondary {
            background: #2a2a2a;
            color: #e9e9e9;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #3a3a3a;
            position: relative;
            overflow: hidden;
        }
        .btn-secondary:hover { 
            background: #3a3a3a; 
            color: #e7d2b8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 210, 184, 0.2);
        }
        .btn-secondary:active {
            transform: translateY(0);
        }
        .cart-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #1a1a1a;
            border-radius: 8px;
            margin-right: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
        }
        .cart-link:hover {
            background-color: #2a2a2a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        .cart-link:active {
            transform: translateY(0);
        }
        .cart-link svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: #ffffff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform 0.3s ease;
        }
        .cart-link:hover svg {
            transform: scale(1.1);
        }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #ff0000;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
            min-width: 16px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .cart-link:hover .cart-badge {
            animation: none;
            transform: scale(1.1);
        }
        
        /* Main Content */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
        }
        
        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #e7d2b8;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 6px;
            position: relative;
        }
        .back-button:hover {
            color: #d4c4a8;
            transform: translateX(-4px);
            background: rgba(231, 210, 184, 0.1);
        }
        .back-button:active {
            transform: translateX(-2px);
        }
        .back-button svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }
        .back-button:hover svg {
            transform: translateX(-2px);
        }
        
        /* Footer */
        .footer { 
            background: #0a0a0a; 
            border-top: 1px solid #1a1a1a; 
            padding: 3rem 0; 
            text-align: center; 
            color: #9f9f9f; 
            margin-top: auto;
        }
        
        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #e9e9e9;
            font-size: 24px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav { 
                position: fixed;
                left: 0;
                right: 0;
                top: 70px;
                display: flex;
                flex-direction: column;
                background: rgba(10, 10, 10, 0.98);
                border-bottom: 1px solid #1a1a1a;
                padding: 20px;
                gap: 16px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 999;
            }
            .nav.active {
                transform: translateX(0);
            }
            .nav a.active::after {
                display: none;
            }
            .mobile-toggle { display: block; }
            .auth-links { 
                flex-wrap: nowrap; 
                gap: 0.5rem; 
            }
            .header-content { 
                padding: 0.75rem 15px; 
                min-height: 60px;
            }
            .btn-outline, .btn-primary {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            .cart-link {
                width: 36px;
                height: 36px;
                margin-right: 6px;
            }
            .cart-link svg {
                width: 18px;
                height: 18px;
            }
        }
        
        @media (max-width: 480px) {
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
        }
        
        /* Admin specific styles */
        .admin-nav {
            background: #1a1a1a;
            border-bottom: 1px solid #2a2a2a;
            padding: 0.5rem 0;
        }
        .admin-nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
            padding: 0 20px;
        }
        .admin-nav a {
            color: #cfcfcf;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }
        .admin-nav a:hover,
        .admin-nav a.active {
            background: #e7d2b8;
            color: #111;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
        }
        .admin-nav a:active {
            transform: translateY(0);
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">L'essence</a>
            <nav class="nav" id="mainNav">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="{{ route('shop.index') }}" class="nav-link">Fragrances</a>
                <a href="{{ route('home') }}#about" class="nav-link">About</a>
                <a href="{{ route('home') }}#contact" class="nav-link">Contact</a>
            </nav>
            <button class="mobile-toggle" id="mobileToggle">☰</button>
            <div class="auth-links">
                @php
                    $cartModel = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->first() : null;
                    $cartQty = 0;
                    if ($cartModel) {
                        $items = $cartModel->items ?? [];
                        foreach ($items as $it) { $cartQty += (int)($it['qty'] ?? 0); }
                    }
                @endphp
                @auth
                    @if(strtolower((string) auth()->user()->role) !== 'admin')
                        <a href="{{ route('cart.index') }}" class="cart-link" title="Cart">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 4H5a1 1 0 0 0-1 1v0a1 1 0 0 0 1 1h1l2.68 9.393A2 2 0 0 0 10.6 17h6.52a2 2 0 0 0 1.93-1.518L21 8H7" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="10" cy="20" r="1.5" fill="#fff"/>
                                <circle cx="18" cy="20" r="1.5" fill="#fff"/>
                            </svg>
                            @if($cartQty > 0)
                                <span class="cart-badge">{{ $cartQty }}</span>
                            @endif
                        </a>
                    @endif
                @else
                    <a href="{{ route('cart.index') }}" class="cart-link" title="Cart">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 4H5a1 1 0 0 0-1 1v0a1 1 0 0 0 1 1h1l2.68 9.393A2 2 0 0 0 10.6 17h6.52a2 2 0 0 0 1.93-1.518L21 8H7" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="20" r="1.5" fill="#fff"/>
                            <circle cx="18" cy="20" r="1.5" fill="#fff"/>
                        </svg>
                        @if($cartQty > 0)
                            <span class="cart-badge">{{ $cartQty }}</span>
                        @endif
                    </a>
                @endauth
                @auth
                    <a href="{{ route('account') }}" class="btn-outline">Account</a>
                    @if(strtolower((string) auth()->user()->role) === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-outline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login.show') }}" class="btn-outline">Sign in</a>
                    <a href="{{ route('register.show') }}" class="btn-primary">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Admin Navigation (only for admin pages) -->
    @if(isset($isAdminPage) && $isAdminPage)
        <div class="admin-nav">
            <div class="admin-nav-content">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">Messages</a>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <p>&copy; 2024 L'essence. All rights reserved. Crafting luxury fragrances for the discerning.</p>
        </div>
    </footer>
    <script>
        (function() {
            const nav = document.getElementById('mainNav');
            const toggle = document.getElementById('mobileToggle');
            const links = document.querySelectorAll('#mainNav .nav-link');

            if (toggle && nav) {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    nav.classList.toggle('active');
                });

                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('header')) {
                        nav.classList.remove('active');
                    }
                });

                // Close after clicking a link
                links.forEach(function(link) {
                    link.addEventListener('click', function() {
                        nav.classList.remove('active');
                    });
                });
            }

            // Active link highlighting
            function setActiveLinkByHashOrPath() {
                var currentHash = window.location.hash;
                var currentPath = window.location.pathname;
                links.forEach(function(link) {
                    link.classList.remove('active');
                    var href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        // Normalize to path + hash for comparison
                        var a = document.createElement('a');
                        a.href = href;
                        if ((a.pathname === currentPath && (!a.hash || a.hash === '')) || (a.hash && a.hash === currentHash)) {
                            link.classList.add('active');
                        }
                    } catch (_) {}
                });
            }

            // Scroll-based highlighting for sections on the page
            function enableScrollSpy() {
                var sections = document.querySelectorAll('section[id]');
                if (!sections.length) return;
                window.addEventListener('scroll', function() {
                    var current = '';
                    sections.forEach(function(section) {
                        var sectionTop = section.offsetTop;
                        if (window.scrollY >= (sectionTop - 100)) {
                            current = section.getAttribute('id');
                        }
                    });
                    links.forEach(function(link) {
                        link.classList.remove('active');
                        var target = link.getAttribute('href');
                        if (target && target === '#' + current) {
                            link.classList.add('active');
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                setActiveLinkByHashOrPath();
                enableScrollSpy();
                window.addEventListener('hashchange', setActiveLinkByHashOrPath);
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>




