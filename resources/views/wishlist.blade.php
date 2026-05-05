@extends('layouts.app')
@section('title', 'Sản phẩm yêu thích')
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm yêu thích</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-heart me-2" style="color:#c9a96e;"></i>Sản phẩm yêu thích</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @if($wishlistItems->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-heart-broken fa-4x text-muted mb-4"></i>
            <h5 class="text-muted">Bạn chưa có sản phẩm yêu thích nào</h5>
            <a href="{{ route('foods.index') }}" class="btn btn-success mt-3">Khám phá sản phẩm</a>
        </div>
    @else
        <div class="row gy-4">
            @foreach($wishlistItems as $item)
                @if($item->food)
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="product-item">
                        <div class="product-img">
                            <a href="{{ route('banhang.chitiet', $item->food->id) }}">
                                <img src="{{ $item->food->image_url }}" alt="{{ $item->food->name }}">
                            </a>
                        </div>
                        <div class="product-info">
                            <h5>
                                <a href="{{ route('banhang.chitiet', $item->food->id) }}" class="text-decoration-none text-dark">
                                    {{ $item->food->name }}
                                </a>
                            </h5>
                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div>
                                    @if($item->food->sale_price)
                                        <div class="price">{{ number_format($item->food->sale_price) }}đ</div>
                                        <div class="original-price"><small><s>{{ number_format($item->food->price) }}đ</s></small></div>
                                    @else
                                        <div class="price">{{ number_format($item->food->price) }}đ</div>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('banhang.addtocart', $item->food->id) }}" class="btn btn-success btn-sm" title="Thêm vào giỏ">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                    <a href="{{ route('wishlist.remove', $item->food->id) }}" class="btn btn-outline-danger btn-sm" title="Xóa khỏi yêu thích">
                                        <i class="fas fa-heart-broken"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
