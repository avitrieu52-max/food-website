@extends('layouts.app')

@section('title', 'Trang chủ - AT10 FASHION')

@section('content')
<div class="hero-slider mb-5">
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @if($slides->count() > 0)
                @foreach($slides as $i => $slide)
                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                    <div class="hero-slide" style="background-image: url('{{ $slide->image_url }}');">
                        <div class="container">
                            <div class="carousel-caption text-start">
                                @if($slide->subtitle)
                                    <p class="text-uppercase mb-2 fw-semibold" style="color:#c9a96e; letter-spacing:3px;">{{ $slide->subtitle }}</p>
                                @endif
                                <h1>{{ $slide->title }}</h1>
                                @if($slide->description)
                                    <p>{{ $slide->description }}</p>
                                @endif
                                @if($slide->link)
                                    <a href="{{ $slide->link }}" class="btn btn-success btn-hero">{{ $slide->button_text ?? 'Xem ngay' }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Fallback slides khi chưa có dữ liệu --}}
                <div class="carousel-item active">
                    <div class="hero-slide" style="background-image: url('https://picsum.photos/id/1062/1600/800');">
                        <div class="container">
                            <div class="carousel-caption text-start">
                                <p class="text-uppercase mb-2 fw-semibold" style="color:#c9a96e; letter-spacing:3px;">Bộ sưu tập mới 2026</p>
                                <h1>Phong cách của bạn<br>Bắt đầu từ đây</h1>
                                <p>Thời trang hiện đại, chất liệu cao cấp, thiết kế tinh tế.</p>
                                <a href="{{ route('foods.index') }}" class="btn btn-success btn-hero">Khám phá ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-slide" style="background-image: url('https://picsum.photos/id/1027/1600/800');">
                        <div class="container">
                            <div class="carousel-caption text-start">
                                <p class="text-uppercase mb-2 fw-semibold" style="color:#c9a96e; letter-spacing:3px;">Thời trang nữ</p>
                                <h1>Thanh lịch &amp; Hiện đại</h1>
                                <a href="{{ route('foods.category', 'ao_nu') }}" class="btn btn-success btn-hero">Xem ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<section id="about" class="container mb-5">
    {{-- Thanh tìm kiếm --}}
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-5">
        <form action="{{ route('banhang.search') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-semibold fs-5">Tìm kiếm sản phẩm</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-lg"
                       placeholder="Nhập tên sản phẩm bạn muốn tìm...">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <div class="section-title">Tại sao chọn chúng tôi</div>
    <div class="row gy-4 align-items-stretch">
        <div class="col-lg-3 col-md-6">
            <div class="about-card text-center">
                <div class="about-icon mx-auto">
                    <i class="fas fa-tshirt fa-2x" style="color:#c9a96e;"></i>
                </div>
                <h5>Chất liệu cao cấp</h5>
                <p>Vải cotton, linen, lụa tự nhiên được chọn lọc kỹ càng, thoáng mát và bền đẹp theo thời gian.</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="about-card text-center">
                <div class="about-icon mx-auto">
                    <i class="fas fa-truck fa-2x" style="color:#c9a96e;"></i>
                </div>
                <h5>Giao hàng nhanh</h5>
                <p>Giao hàng toàn quốc trong 2-3 ngày. Miễn phí vận chuyển cho đơn hàng từ 500.000đ.</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="about-card text-center">
                <div class="about-icon mx-auto">
                    <i class="fas fa-undo fa-2x" style="color:#c9a96e;"></i>
                </div>
                <h5>Đổi trả dễ dàng</h5>
                <p>Chính sách đổi trả trong 30 ngày nếu sản phẩm lỗi hoặc không vừa size. Không cần lý do.</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="about-card text-center">
                <div class="about-icon mx-auto">
                    <i class="fas fa-star fa-2x" style="color:#c9a96e;"></i>
                </div>
                <h5>Thiết kế độc quyền</h5>
                <p>Bộ sưu tập được thiết kế riêng, cập nhật xu hướng thời trang mới nhất mỗi mùa.</p>
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="banner-card position-relative overflow-hidden">
        <img src="https://picsum.photos/id/1035/1600/800" alt="Fashion Banner">
        <div class="banner-content">
            <div class="banner-copy">
                <span class="text-uppercase fw-semibold mb-3 d-inline-block" style="color:#c9a96e; letter-spacing:3px;">Sale mùa hè</span>
                <h2>Giảm đến 50%<br>Bộ sưu tập hè</h2>
                <p>Hàng trăm mẫu áo, quần, váy đầm đang được giảm giá mạnh. Nhanh tay sở hữu ngay hôm nay!</p>
                <a href="{{ route('foods.index', ['sort' => 'newest']) }}" class="btn btn-light btn-hero">Mua ngay</a>
            </div>
        </div>
    </div>
</section>

{{-- Section: Sản phẩm mới --}}
<section id="new-products" class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-title mb-0">Hàng mới về</div>
        <a href="{{ route('foods.index') }}" class="btn btn-outline-success btn-sm">Xem tất cả</a>
    </div>
    <div class="row gy-4">
        @foreach($new_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

{{-- Section: Sản phẩm khuyến mãi --}}
<section id="promotion-products" class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-title mb-0">Đang giảm giá</div>
        <a href="{{ route('foods.index', ['sort' => 'newest']) }}" class="btn btn-outline-success btn-sm">Xem tất cả</a>
    </div>
    <div class="row gy-4">
        @foreach($promotion_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

{{-- Section: Sản phẩm nổi bật --}}
<section id="top-products" class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-title mb-0">Sản phẩm nổi bật</div>
        <a href="{{ route('foods.index') }}" class="btn btn-outline-success btn-sm">Xem tất cả</a>
    </div>
    <div class="row gy-4">
        @foreach($top_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

{{-- Section: Tất cả sản phẩm --}}
<section id="products" class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-title mb-0">Tất cả sản phẩm</div>
        <a href="{{ route('foods.index') }}" class="btn btn-success btn-sm">Xem thêm</a>
    </div>
    <div class="row gy-4">
        @foreach($all_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

<section class="container mb-5" id="guide">
    <div class="newsletter-card p-5 text-center">
        <h3 class="mb-3">Đăng ký nhận ưu đãi</h3>
        <p class="mb-4">Nhận thông tin bộ sưu tập mới và mã giảm giá độc quyền qua email mỗi tuần.</p>
        <form class="row g-3 justify-content-center">
            <div class="col-md-6">
                <input type="email" class="form-control form-control-lg" placeholder="Email của bạn">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-lg w-100">Đăng ký</button>
            </div>
        </form>
    </div>
</section>

<section class="container mb-5" id="contact">
    <div class="section-title">Liên hệ với chúng tôi</div>
    @if(session('contact_success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('contact_success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="about-card h-100">
                <h5><i class="fas fa-map-marker-alt me-2" style="color:#c9a96e;"></i>Thông tin liên hệ</h5>
                <p class="mb-1"><i class="fas fa-phone me-2"></i>Hotline: 0123.456.789</p>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i>Email: contact@demo.com</p>
                <p class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>123 Nguyễn Văn Linh, Đà Nẵng</p>
                <h5><i class="far fa-clock me-2" style="color:#c9a96e;"></i>Giờ mở cửa</h5>
                <p class="mb-1">Thứ 2 - Thứ 6: 8:00 - 21:00</p>
                <p class="mb-0">Thứ 7 - Chủ nhật: 9:00 - 22:00</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="about-card">
                <h5 class="mb-4"><i class="fas fa-paper-plane me-2" style="color:#c9a96e;"></i>Gửi tin nhắn cho chúng tôi</h5>
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="form-control @error('contact_name') is-invalid @enderror" required>
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="form-control @error('contact_email') is-invalid @enderror" required>
                            @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="contact_message" class="form-control @error('contact_message') is-invalid @enderror" rows="5" required maxlength="1000" placeholder="Nhập nội dung tin nhắn...">{{ old('contact_message') }}</textarea>
                            @error('contact_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success px-5">
                                <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.tablinks').forEach(function(button) {
        button.addEventListener('click', function() {
            document.querySelectorAll('.tablinks').forEach(function(btn){ btn.classList.remove('active'); });
            document.querySelectorAll('.tabcontent').forEach(function(content){ content.style.display = 'none'; });
            document.getElementById(this.dataset.target).style.display = 'block';
            this.classList.add('active');
        });
    });
</script>
@endpush
@endsection
