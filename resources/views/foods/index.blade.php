@extends('layouts.app')

@section('title', 'Trang chủ - AT10 FOOD')

@section('content')
<div class="hero-slider mb-5">
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: url('https://at10.mediawz.com/wp-content/uploads/2019/06/ms_banner_img1.jpg');">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <p class="text-uppercase text-white mb-2 fw-semibold">Thực phẩm hữu cơ 1</p>
                            <h1>Thực phẩm tốt cho sức khỏe của bạn</h1>
                            <p>Chúng tôi mang đến sản phẩm an toàn, tươi mới và hoàn toàn tự nhiên cho gia đình Việt.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="#products" class="btn btn-success btn-hero">Xem sản phẩm</a>
                                <a href="#about" class="btn btn-outline-light btn-hero">Tìm hiểu thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('https://at10.mediawz.com/wp-content/uploads/2019/06/ms_banner_img2.jpg');">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <p class="text-uppercase text-white mb-2 fw-semibold">Thực phẩm hữu cơ 2</p>
                            <h1>Chất lượng luôn là ưu tiên hàng đầu</h1>
                            <p>Thực phẩm sạch, dây chuyền sản xuất chuẩn an toàn và phục vụ chu đáo.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="#products" class="btn btn-success btn-hero">Xem sản phẩm</a>
                                <a href="#about" class="btn btn-outline-light btn-hero">Tìm hiểu thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('https://at10.mediawz.com/wp-content/uploads/2019/06/ms_banner_img3.jpg');">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <p class="text-uppercase text-white mb-2 fw-semibold">Thực phẩm hữu cơ 3</p>
                            <h1>Sống khỏe mỗi ngày với thực phẩm thiên nhiên</h1>
                            <p>Sản phẩm tươi ngon, không chất bảo quản và giao hàng nhanh chóng.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="#products" class="btn btn-success btn-hero">Xem sản phẩm</a>
                                <a href="#about" class="btn btn-outline-light btn-hero">Tìm hiểu thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    <div class="section-title">Giới thiệu</div>
    <div class="row gy-4 align-items-center">
        <div class="col-lg-4">
            <div class="about-card mb-4">
                <div class="about-icon"><img src="https://at10.mediawz.com/wp-content/uploads/2019/06/hab_left_icon_1.png" alt="100% Tự Nhiên"></div>
                <h5>100% Tự Nhiên</h5>
                <p>Chúng tôi quan tâm đến những gì bạn ăn. Thực phẩm của chúng tôi tốt cho cơ thể và ngon miệng.</p>
            </div>
            <div class="about-card">
                <div class="about-icon"><img src="https://at10.mediawz.com/wp-content/uploads/2019/06/hab_left_icon_2.png" alt="Luôn tươi mới"></div>
                <h5>Luôn tươi mới</h5>
                <p>Mỗi sản phẩm đều được chọn lọc nghiêm ngặt và giao đến tay bạn trong tình trạng tốt nhất.</p>
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <img src="https://at10.mediawz.com/wp-content/uploads/2019/06/hab_center_img.jpg" alt="Giới thiệu" class="img-fluid rounded-4 shadow-lg">
        </div>
        <div class="col-lg-4">
            <div class="about-card mb-4">
                <div class="about-icon"><img src="https://at10.mediawz.com/wp-content/uploads/2019/06/hab_right_icon_1.png" alt="Sản phẩm tự nhiên"></div>
                <h5>Sản phẩm tự nhiên</h5>
                <p>Nguyên liệu tự nhiên, an toàn và phù hợp cho mọi thành viên trong gia đình.</p>
            </div>
            <div class="about-card">
                <div class="about-icon"><img src="https://at10.mediawz.com/wp-content/uploads/2019/06/hab_right_icon_2.png" alt="Chất lượng tốt nhất"></div>
                <h5>Chất lượng tốt nhất</h5>
                <p>Quy trình kiểm định nghiêm ngặt đảm bảo bạn nhận được thực phẩm chất lượng cao.</p>
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="banner-card position-relative overflow-hidden">
        <img src="https://at10.mediawz.com/wp-content/uploads/2020/08/hbanner1_bg_down.jpg" alt="Thực phẩm tốt">
        <div class="banner-content">
            <div class="banner-copy">
                <span class="text-uppercase text-white fw-semibold mb-3 d-inline-block">Thực phẩm tốt</span>
                <h2>Cảm xúc vui</h2>
                <p>Thực phẩm của chúng tôi luôn tươi mới, không chất độc hại, mang đến dinh dưỡng hoàn hảo mỗi ngày.</p>
                <a href="#products" class="btn btn-light btn-hero">Xem ngay</a>
            </div>
        </div>
    </div>
</section>

{{-- Section: Sản phẩm mới --}}
<section id="new-products" class="container mb-5">
    <div class="section-title">Sản phẩm mới</div>
    <p class="mb-4 text-muted">{{ count($new_products) }} sản phẩm mới vừa cập bến</p>
    <div class="row gy-4">
        @foreach($new_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $new_products->links() }}</div>
</section>

{{-- Section: Sản phẩm khuyến mãi --}}
<section id="promotion-products" class="container mb-5">
    <div class="section-title">Sản phẩm khuyến mãi</div>
    <div class="row gy-4">
        @foreach($promotion_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

{{-- Section: Sản phẩm nổi bật (Top) --}}
<section id="top-products" class="container mb-5">
    <div class="section-title">Sản phẩm đề nghị</div>
    <div class="row gy-4">
        @foreach($top_products as $food)
            <div class="col-xl-3 col-md-4 col-sm-6">
                @include('foods._product_item', ['food' => $food])
            </div>
        @endforeach
    </div>
</section>

<section class="container mb-5" id="guide">
    <div class="newsletter-card p-5 text-center">
        <h3 class="mb-3">Đăng ký nhận tin</h3>
        <p class="mb-4">Nhận thông tin mới nhất về sản phẩm và chương trình ưu đãi mỗi ngày.</p>
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
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="about-card">
                <h5>Liên hệ</h5>
                <p>Hotline: 0123.456.789</p>
                <p>Email: contact@demo.com</p>
                <p>Địa chỉ: 123 Nguyễn Văn Linh, Đà Nẵng</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="about-card">
                <h5>Giờ mở cửa</h5>
                <p>8:00 - 22:00 tất cả các ngày trong tuần.</p>
                <p>Chúng tôi luôn sẵn sàng phục vụ bạn với thực phẩm sạch và dịch vụ tận tâm.</p>
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