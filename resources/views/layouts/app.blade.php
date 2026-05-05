<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AT10 Fashion - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @php use Illuminate\Support\Facades\Session; @endphp
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: #f8f5f2;
            color: #2c2c2c;
        }
        .topbar {
            background: #1a1a1a;
            color: #c9a96e;
            font-size: 0.95rem;
        }
        .topbar a {
            color: inherit;
        }
        .topbar .topbar-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1.5rem;
        }
        .topbar .topbar-item svg {
            width: 16px;
            height: 16px;
        }
        #header {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .navbar-brand img {
            max-height: 62px;
        }
        .hd-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .hd-actions a {
            color: #2c2c2c;
            font-weight: 500;
        }
        .hd-actions a:hover {
            color: #c9a96e;
        }
        .hero-slider {
            position: relative;
            overflow: hidden;
        }
        .hero-slide {
            min-height: 78vh;
            display: flex;
            align-items: center;
            background-size: cover;
            background-position: center;
        }
        .hero-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(10, 10, 10, 0.48);
        }
        .hero-slide .carousel-caption {
            position: relative;
            z-index: 2;
            bottom: auto;
            transform: none;
            text-align: left;
            max-width: 540px;
        }
        .hero-slide .carousel-caption h1 {
            font-size: 3.4rem;
            font-weight: 800;
            line-height: 1.02;
            color: #ffffff;
        }
        .hero-slide .carousel-caption p {
            color: rgba(255,255,255,0.92);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        .hero-slide .carousel-caption .btn {
            border-radius: 50px;
            padding: 0.95rem 2rem;
        }
        .section-title {
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 3.5rem;
            position: relative;
            display: inline-block;
            color: #2c2c2c;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -15px;
            width: 80px;
            height: 5px;
            background: linear-gradient(to right, #c9a96e, #e8c98a);
            border-radius: 999px;
        }
        .about-card,
        .banner-card,
        .product-item,
        .newsletter-card {
            border-radius: 1rem;
            background: white;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .about-card {
            padding: 2.5rem;
            min-height: 100%;
        }
        .about-card .about-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(201,169,110,0.12);
            display: grid;
            place-items: center;
            margin-bottom: 1.5rem;
        }
        .about-card .about-icon img {
            width: 44px;
            height: 44px;
        }
        .about-card h5 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.85rem;
            color: #2c2c2c;
        }
        .about-card p {
            color: #6b6b6b;
            line-height: 1.8;
            margin-bottom: 0;
        }
        .banner-card {
            position: relative;
            overflow: hidden;
            min-height: 420px;
        }
        .banner-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.78);
        }
        .banner-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.32);
        }
        .banner-card .banner-content {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            padding: 3rem;
        }
        .banner-copy h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1rem;
        }
        .banner-copy p {
            color: rgba(255,255,255,0.92);
            font-size: 1.05rem;
            margin-bottom: 1.75rem;
            max-width: 520px;
        }
        .banner-copy .btn {
            border-radius: 50px;
            padding: 0.95rem 2.2rem;
        }
        .product-tab {
            background: white;
            border-radius: 50px;
            padding: 0.5rem;
            display: inline-flex;
            box-shadow: 0 8px 20px rgba(201,169,110,0.15);
            margin-bottom: 3rem;
            gap: 0.5rem;
        }
        .product-tab button {
            background: transparent;
            border: none;
            border-radius: 50px;
            padding: 0.8rem 1.8rem;
            color: #6b6b6b;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .product-tab button:hover {
            background: rgba(201,169,110,0.1);
        }
        .product-tab button.active {
            background: linear-gradient(135deg, #c9a96e, #e8c98a);
            color: white;
            box-shadow: 0 4px 12px rgba(201,169,110,0.35);
        }
        .product-item {
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .product-img {
            position: relative;
            overflow: hidden;
            height: 280px;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-item:hover .product-img img {
            transform: scale(1.08);
        }
        .product-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-info h5 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #2c2c2c;
            line-height: 1.4;
        }
        .product-info p {
            color: #6b6b6b;
            margin-bottom: 1rem;
            min-height: 45px;
            flex: 0;
            font-size: 0.95rem;
        }
        .product-info .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #c9a96e;
            margin-bottom: 0.75rem;
        }
        .product-info .original-price {
            font-size: 0.9rem;
            color: #aaa;
        }
        .product-actions .btn {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
        }
        .btn-success, .btn-outline-success:hover {
            background-color: #c9a96e !important;
            border-color: #c9a96e !important;
            color: #fff !important;
        }
        .btn-outline-success {
            color: #c9a96e !important;
            border-color: #c9a96e !important;
        }
        .text-success { color: #c9a96e !important; }
        .badge.bg-success { background-color: #c9a96e !important; }
        .footer {
            background: #1a1a1a;
            color: rgba(255,255,255,0.75);
            margin-top: 5rem;
            padding: 3rem 0 2rem;
        }
        .footer a {
            color: rgba(255,255,255,0.6);
        }
        .footer a:hover {
            color: #c9a96e;
        }
        @media (max-width: 992px) {
            .hero-slide .carousel-caption {
                max-width: 100%;
                padding: 0 1rem;
            }
            .hero-slide .carousel-caption h1 {
                font-size: 2.6rem;
            }
            .banner-card .banner-content {
                padding: 2rem;
            }
        }
        @media (max-width: 768px) {
            .topbar .topbar-item {
                display: block;
                margin-right: 0;
            }
            .hd-actions {
                gap: 0.5rem;
            }
            .hero-slide {
                min-height: 56vh;
            }
            .hero-slide .carousel-caption h1 {
                font-size: 2rem;
            }
            .banner-copy h2 {
                font-size: 2rem;
            }
            .product-tab {
                flex-wrap: wrap;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header id="header">
        <div class="topbar py-2">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="topbar-item"><i class="fas fa-phone-alt"></i> 0123.456.789</div>
                    <div class="topbar-item"><i class="fas fa-envelope"></i> contact@demo.com</div>
                    <div class="topbar-item"><i class="far fa-clock"></i> Mở cửa từ 8:00 - 22:00</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <span style="color:#c9a96e;">Chào bạn {{ Auth::user()->full_name }}</span>
                        <a href="{{ route('getlogout') }}" class="text-decoration-none" style="color:#6b6b6b;">Đăng xuất</a>
                    @else
                        <a href="{{ route('getlogin') }}" class="text-decoration-none" style="color:#c9a96e;">Đăng nhập</a>
                        <a href="{{ route('getsignin') }}" class="text-decoration-none" style="color:#c9a96e;">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}" style="color:#1a1a1a; letter-spacing:2px;">
                    <i class="fas fa-tshirt me-2" style="color:#c9a96e;"></i>AT10 <span style="color:#c9a96e;">FASHION</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavigation">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">Giới thiệu</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('foods.*') ? 'active' : '' }}" href="{{ route('foods.index') }}" data-bs-toggle="dropdown">
                                Sản phẩm
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('foods.index') }}">Tất cả sản phẩm</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @foreach(\App\Models\Food::getCategories() as $key => $label)
                                <li><a class="dropdown-item" href="{{ route('foods.category', $key) }}">{{ $label }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('banhang.giohang') ? 'active' : '' }}" href="{{ route('banhang.giohang') }}">Giỏ hàng</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Liên hệ</a></li>
                    </ul>
                    <div class="hd-actions">
                        <a href="{{ route('banhang.search') }}" class="action-link" data-bs-toggle="collapse" data-bs-target="#searchBar" aria-expanded="false">
                            <i class="fas fa-search"></i>
                        </a>
                        @auth
                        <a href="{{ route('wishlist.index') }}" class="action-link position-relative" title="Yêu thích">
                            <i class="fas fa-heart" style="color:#c9a96e;"></i>
                        </a>
                        <a href="{{ route('account.index') }}" class="action-link" title="Tài khoản">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        @endauth
                        <a href="{{ route('banhang.giohang') }}" class="action-link position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            @if(Session::has('cart'))
                                <span class="badge bg-success rounded-pill position-absolute top-0 start-100 translate-middle">{{ Session::get('cart')->totalQty ?? 0 }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="collapse" id="searchBar">
            <div class="container py-2">
                <form action="{{ route('banhang.search') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}" autofocus>
                    <button type="submit" class="btn btn-success px-4"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

<main>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @yield('content')
</main>

<!-- Footer -->
<footer class="footer py-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <h4 class="text-white mb-3"><i class="fas fa-tshirt me-2"></i>AT10 FASHION</h4>
                <p>Chuyên cung cấp thời trang nam nữ chất lượng cao, phong cách hiện đại và giá cả hợp lý. Cam kết mang đến sản phẩm tốt nhất cho phong cách của bạn.</p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2">
                <h5 class="text-white">Danh mục</h5>
                <ul class="list-unstyled mt-3">
                    @foreach(\App\Models\Food::getCategories() as $key => $label)
                    <li><a href="{{ route('foods.category', $key) }}" class="text-decoration-none" style="color:rgba(255,255,255,0.6);">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-3">
                <h5 class="text-white">Thông tin</h5>
                <ul class="list-unstyled mt-3">
                    <li><a href="#about" class="text-white-50 text-decoration-none">Giới thiệu</a></li>
                    <li><a href="#products" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                    <li><a href="#contact" class="text-white-50 text-decoration-none">Liên hệ</a></li>
                    <li><a href="#guide" class="text-white-50 text-decoration-none">Hướng dẫn</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5 class="text-white">Liên hệ</h5>
                <ul class="list-unstyled mt-3 text-white-50">
                    <li><i class="fas fa-map-marker-alt me-2"></i>123 Nguyễn Văn Linh, Đà Nẵng</li>
                    <li><i class="fas fa-phone-alt me-2"></i>0123 456 789</li>
                    <li><i class="fas fa-envelope me-2"></i>contact@demo.com</li>
                </ul>
            </div>
        </div>
        <hr class="border-white-25 mt-4">
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div class="text-white-50">© 2026 AT10 FASHION - All rights reserved.</div>
            <a href="{{ route('admin.getLogin') }}" class="text-white-50 text-decoration-none small">
                <i class="fas fa-lock me-1"></i>Quản trị
            </a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

@if(env('FACEBOOK_PAGE_ID'))
<!-- Facebook Messenger Customer Chat Plugin -->
<div id="fb-root"></div>
<div class="fb-customerchat"
     attribution="biz_inbox"
     page_id="{{ env('FACEBOOK_PAGE_ID') }}">
</div>
<script>
  window.fbAsyncInit = function() {
    FB.init({ xfbml: true, version: 'v18.0' });
  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
@endif
</body>
</html>