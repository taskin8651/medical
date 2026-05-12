@php
    $setting = $frontendSetting ?? null;
    $siteName = $setting->site_name ?? 'Medion';
    $siteTitle = $setting->site_title ?? $siteName . ' - Health And Medical Store';
    $email = $setting->email ?? 'info@example.com';
    $phone = $setting->phone ?? '+2 123 654 7898';
    $address = $setting->address ?? '25/B Milford Road, New York';
    $logo = !empty($setting?->logo) ? asset('storage/' . $setting->logo) : asset('assets/img/logo/logo.png');
    $favicon = !empty($setting?->favicon) ? asset('storage/' . $setting->favicon) : asset('assets/img/logo/favicon.png');
    $footerDescription = $setting->footer_description ?? 'Your trusted destination for healthcare, wellness, medical supplies and everyday essentials.';
    $copyright = $setting->footer_copyright ?? ('Copyright ' . date('Y') . ' ' . $siteName . ' All Rights Reserved.');
    $categories = ($frontendCategories ?? collect())->take(12);
    $cart = $cartItems ?? [];
    $cartQty = $cartCount ?? 0;
    $cartAmount = $cartTotal ?? 0;
    $categoryIcons = [
        'medicine' => 'fa-capsules',
        'medical' => 'fa-kit-medical',
        'beauty' => 'fa-spa',
        'baby' => 'fa-baby',
        'health' => 'fa-heart-pulse',
        'food' => 'fa-bowl-food',
        'nutrition' => 'fa-apple-whole',
        'lab' => 'fa-flask-vial',
        'fitness' => 'fa-dumbbell',
        'vitamin' => 'fa-tablets',
        'supplement' => 'fa-tablets',
        'pet' => 'fa-shield-heart',
    ];
    $iconFor = function ($name) use ($categoryIcons) {
        $name = strtolower($name ?? '');
        foreach ($categoryIcons as $needle => $icon) {
            if (str_contains($name, $needle)) {
                return $icon;
            }
        }
        return 'fa-box-medical';
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $setting->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $setting->meta_keywords ?? '' }}">
    <title>@yield('title', $setting->meta_title ?? $siteTitle)</title>
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .main-navigation .navbar > .container {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .main-navigation .navbar-brand img {
            max-height: 46px;
            object-fit: contain;
        }

        .main-navigation .navbar-collapse {
            flex-grow: 1;
        }

        .main-navigation .navbar-nav {
            align-items: center;
        }

        .navbar .dropdown-toggle::after {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
        }

        .main-navigation .nav-right {
            margin-left: auto;
            white-space: nowrap;
        }

        .main-navigation .nav-right-link .cart-count {
            position: absolute;
            top: -10px;
            right: -12px;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            border-radius: 50px;
            background: var(--theme-color);
            color: var(--color-white);
            font-size: 11px;
            text-align: center;
        }

        .main-category li a .menu-category-icon {
            width: 25px;
            min-width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--theme-color);
        }

        .main-category li a .menu-arrow {
            margin-left: auto;
            color: var(--body-text-color);
        }

        @media all and (max-width: 1399px) {
            .main-navigation .nav-right-text {
                display: none;
            }
        }

        @media all and (max-width: 991px) {
            .main-navigation .navbar > .container {
                gap: 12px;
            }

            .main-navigation .mobile-menu-right {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-top-wrapper">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6 col-xl-5">
                            <div class="header-top-left">
                                <ul class="header-top-list">
                                    <li><a href="mailto:{{ $email }}"><i class="fas fa-envelope"></i> {{ $email }}</a></li>
                                    <li><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><i class="fas fa-headset"></i> {{ $phone }}</a></li>
                                    <li class="help"><a href="{{ route('shop') }}"><i class="fas fa-circle-question"></i> Need Help?</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-7">
                            <div class="header-top-right">
                                <ul class="header-top-list">
                                    <li><a href="{{ route('shop') }}"><i class="fas fa-tags"></i> Daily Deal</a></li>
                                    <li>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-indian-rupee-sign"></i> INR
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">INR</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="social">
                                        <div class="header-top-social">
                                            <span>Follow Us: </span>
                                            @foreach (['facebook' => 'facebook-f', 'twitter' => 'x-twitter', 'instagram' => 'instagram', 'linkedin' => 'linkedin-in'] as $field => $icon)
                                                @if (!empty($setting?->{$field}))
                                                    <a href="{{ $setting->{$field} }}" target="_blank" rel="noopener"><i class="fab fa-{{ $icon }}"></i></a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-5 col-lg-3 col-xl-3">
                        <div class="header-middle-logo">
                            <a class="navbar-brand" href="{{ route('home') }}">
                                <img src="{{ $logo }}" alt="{{ $siteName }}">
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-lg-6 col-xl-5">
                        <div class="header-middle-search">
                            <form action="{{ route('shop') }}" method="GET">
                                <div class="search-content">
                                    <select class="select" name="category">
                                        <option value="">All Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="search" class="form-control" placeholder="Search Here..." value="{{ request('search') }}">
                                    <button type="submit" class="search-btn"><i class="fas fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-7 col-lg-3 col-xl-4">
                        <div class="header-middle-right">
                            <ul class="header-middle-list">
                                <li>
                                    <a href="{{ auth()->check() ? route('admin.home') : route('login') }}" class="list-item">
                                        <div class="list-item-icon"><i class="fas fa-circle-user"></i></div>
                                        <div class="list-item-info">
                                            <h6>{{ auth()->check() ? 'Dashboard' : 'Sign In' }}</h6>
                                            <h5>Account</h5>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('shop') }}" class="list-item">
                                        <div class="list-item-icon"><i class="fas fa-heart"></i><span>0</span></div>
                                        <div class="list-item-info">
                                            <h6>Wishlist</h6>
                                            <h5>My Items</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-cart">
                                    <a href="{{ route('cart.index') }}" class="shop-cart list-item">
                                        <div class="list-item-icon"><i class="fas fa-bag-shopping"></i><span>{{ $cartQty }}</span></div>
                                        <div class="list-item-info">
                                            <h6>₹{{ number_format($cartAmount, 2) }}</h6>
                                            <h5>My Cart</h5>
                                        </div>
                                    </a>
                                    <div class="dropdown-cart-menu">
                                        <div class="dropdown-cart-header">
                                            <span>{{ count($cart) }} Items</span>
                                            <a href="{{ route('cart.index') }}">View Cart</a>
                                        </div>
                                        <ul class="dropdown-cart-list">
                                            @forelse (array_slice($cart, 0, 3, true) as $item)
                                                <li>
                                                    <div class="dropdown-cart-item">
                                                        <div class="cart-img">
                                                            <a href="{{ !empty($item['slug']) ? route('shop.show', $item['slug']) : route('shop') }}">
                                                                <img src="{{ $item['image'] ?? asset('assets/img/product/01.png') }}" alt="{{ $item['name'] ?? 'Product' }}">
                                                            </a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h4><a href="{{ !empty($item['slug']) ? route('shop.show', $item['slug']) : route('shop') }}">{{ $item['name'] ?? 'Product' }}</a></h4>
                                                            <p class="cart-qty">{{ $item['quantity'] ?? 1 }}x - <span class="cart-amount">₹{{ number_format($item['price_with_gst'] ?? $item['price'] ?? 0, 2) }}</span></p>
                                                        </div>
                                                        <a href="{{ route('cart.remove', $item['id'] ?? 0) }}" class="cart-remove" title="Remove this item"><i class="fas fa-circle-xmark"></i></a>
                                                    </div>
                                                </li>
                                            @empty
                                                <li><div class="dropdown-cart-item"><div class="cart-info"><h4>Your cart is empty</h4></div></div></li>
                                            @endforelse
                                        </ul>
                                        <div class="dropdown-cart-bottom">
                                            <div class="dropdown-cart-total">
                                                <span>Total</span>
                                                <span class="total-amount">₹{{ number_format($cartAmount, 2) }}</span>
                                            </div>
                                            <a href="{{ route('checkout.index') }}" class="theme-btn">Checkout</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container position-relative">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ $logo }}" class="logo-display" alt="{{ $siteName }}">
                        <img src="{{ $logo }}" class="logo-scrolled" alt="{{ $siteName }}">
                    </a>
                    <div class="category-all">
                        <button class="category-btn" type="button">
                            <i class="fas fa-list-ul"></i><span>All Categories</span>
                        </button>
                        <ul class="main-category">
                            @forelse ($categories as $category)
                                <li>
                                    <a href="{{ route('category.show', $category->slug) }}">
                                        <i class="fas {{ $iconFor($category->name) }} menu-category-icon"></i>
                                        <span>{{ $category->name }}</span>
                                        @if ($category->subcategories->isNotEmpty())
                                            <i class="fas fa-angle-right menu-arrow"></i>
                                        @endif
                                    </a>
                                    @if ($category->subcategories->isNotEmpty())
                                        <div class="sub-category-mega">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="category-single">
                                                        <h6 class="category-title-text">{{ $category->name }}</h6>
                                                        <div class="category-link">
                                                            @foreach ($category->subcategories->take(12) as $subcategory)
                                                                <a href="{{ route('shop', ['category' => $category->id]) }}">{{ $subcategory->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="category-img">
                                                        <a href="{{ route('category.show', $category->slug) }}">
                                                            <img src="{{ $category->getFirstMediaUrl('category') ?: asset('assets/img/banner/mini-banner-1.jpg') }}" alt="{{ $category->name }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @empty
                                <li><a href="{{ route('shop') }}"><i class="fas fa-box-medical menu-category-icon"></i><span>All Products</span></a></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mobile-menu-right">
                        <a class="nav-right-link search-box-outer" href="#"><i class="fas fa-magnifying-glass"></i></a>
                        <a class="nav-right-link" href="{{ route('cart.index') }}"><i class="fas fa-bag-shopping"></i><span class="cart-count">{{ $cartQty }}</span></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="collapse navbar-collapse" id="main_nav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('shop*') ? 'active' : '' }}" href="{{ route('shop') }}">Shop</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('categories*') || request()->routeIs('category.*') ? 'active' : '' }}" href="{{ route('categories') }}">Categories</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
                        </ul>
                        <div class="nav-right">
                            <a class="nav-right-link search-box-outer" href="#"><i class="fas fa-magnifying-glass"></i></a>
                            <a class="nav-right-link" href="{{ route('cart.index') }}"><i class="fas fa-bag-shopping"></i><span class="cart-count">{{ $cartQty }}</span></a>
                            <a class="nav-right-link" href="{{ route('shop') }}"><i class="fas fa-clock-rotate-left"></i> <span class="nav-right-text">Recently Viewed</span></a>
                            <a class="nav-right-link" href="{{ route('cart.index') }}"><i class="fas fa-truck-fast"></i> <span class="nav-right-text">Track My Order</span></a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="search-popup">
        <button class="close-search"><span class="fas fa-xmark"></span></button>
        <form action="{{ route('shop') }}" method="GET">
            <div class="form-group">
                <input type="search" name="search" class="form-control" placeholder="Search Here..." value="{{ request('search') }}" required>
                <button type="submit"><i class="fas fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>

    <main class="main">
        @yield('content')
    </main>

    <footer class="footer-area ft-bg">
        <div class="footer-widget">
            <div class="container">
                <div class="row footer-widget-wrapper pt-100 pb-40">
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box about-us">
                            <a href="{{ route('home') }}" class="footer-logo">
                                <img src="{{ $logo }}" alt="{{ $siteName }}">
                            </a>
                            <p class="mb-3">{{ $footerDescription }}</p>
                            <ul class="footer-contact">
                                <li><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><i class="fas fa-phone"></i>{{ $phone }}</a></li>
                                <li><i class="fas fa-location-dot"></i>{{ $address }}</li>
                                <li><a href="mailto:{{ $email }}"><i class="fas fa-envelope"></i>{{ $email }}</a></li>
                                <li><i class="fas fa-clock"></i>Mon-Fri (9.00AM - 8.00PM)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Quick Links</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('shop') }}">Shop</a></li>
                                <li><a href="{{ route('categories') }}">Categories</a></li>
                                <li><a href="{{ route('blog.index') }}">Update News</a></li>
                                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Browse Category</h4>
                            <ul class="footer-list">
                                @forelse ($categories->take(7) as $category)
                                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                                @empty
                                    <li><a href="{{ route('shop') }}">All Products</a></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Support Center</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                                <li><a href="{{ route('checkout.index') }}">How To Buy</a></li>
                                <li><a href="{{ route('cart.index') }}">Track Your Order</a></li>
                                <li><a href="{{ route('shop') }}">Returns Policy</a></li>
                                <li><a href="{{ route('shop') }}">Support Center</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Get Mobile App</h4>
                            <p>{{ $siteName }} app is now available on App Store and Google Play.</p>
                            <div class="footer-download">
                                <h5>Download Our Mobile App</h5>
                                <div class="footer-download-btn">
                                    <a href="#"><i class="fab fa-google-play"></i><div class="download-btn-info"><span>Get It On</span><h6>Google Play</h6></div></a>
                                    <a href="#"><i class="fab fa-app-store-ios"></i><div class="download-btn-info"><span>Get It On</span><h6>App Store</h6></div></a>
                                </div>
                            </div>
                            <div class="footer-payment mt-20">
                                <span>We Accept:</span>
                                <img src="{{ asset('assets/img/payment/amex.svg') }}" alt="Payment">
                                <img src="{{ asset('assets/img/payment/paypal-2.svg') }}" alt="Payment">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="copyright-wrap">
                    <div class="row">
                        <div class="col-12 col-lg-6 align-self-center">
                            <p class="copyright-text">&copy; {{ $copyright }}</p>
                        </div>
                        <div class="col-12 col-lg-6 align-self-center">
                            <div class="footer-social">
                                <span>Follow Us:</span>
                                @foreach (['facebook' => 'facebook-f', 'twitter' => 'x-twitter', 'linkedin' => 'linkedin-in', 'youtube' => 'youtube', 'instagram' => 'instagram'] as $field => $icon)
                                    @if (!empty($setting?->{$field}))
                                        <a href="{{ $setting->{$field} }}" target="_blank" rel="noopener"><i class="fab fa-{{ $icon }}"></i></a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <a href="#" id="scroll-top"><i class="fas fa-arrow-up"></i></a>

    <div class="modal quickview fade" id="quickview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="quickview" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-xmark"></i></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="quickview-img"><img src="{{ asset('assets/img/product/04.png') }}" alt="Product"></div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="quickview-content">
                                <h4 class="quickview-title">Product Details</h4>
                                <div class="quickview-rating">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-stroke"></i><i class="far fa-star"></i>
                                    <span class="rating-count"> (4 Customer Reviews)</span>
                                </div>
                                <div class="quickview-cart"><a href="{{ route('shop') }}" class="theme-btn">View Products</a></div>
                                <div class="quickview-social">
                                    <span>Share:</span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter-up.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
