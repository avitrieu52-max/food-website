@extends('layouts.app')

@section('title', $food->name)

@php use Illuminate\Support\Facades\Auth; @endphp

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($food->image_url)
            <img src="{{ $food->image_url }}" class="img-fluid rounded" alt="{{ $food->name }}">
        @else
            <img src="https://via.placeholder.com/500x400?text=No+Image" class="img-fluid rounded" alt="No image">
        @endif
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('foods.category', $food->category) }}">
                        {{ $food->category_label }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $food->name }}</li>
            </ol>
        </nav>
        
        <h1 class="mb-3">{{ $food->name }}</h1>
        
        <div class="mb-4">
            @if($food->sale_price)
                <h2 class="sale-price d-inline">{{ number_format($food->sale_price) }}đ</h2>
                <span class="old-price h4">{{ number_format($food->price) }}đ</span>
            @else
                <h2 class="sale-price">{{ number_format($food->price) }}đ</h2>
            @endif
        </div>
        
        <div class="mb-4">
            <span class="badge bg-secondary">Còn lại: {{ $food->stock }} sản phẩm</span>
            @if($food->is_featured)
                <span class="badge bg-warning text-dark">Sản phẩm nổi bật</span>
            @endif
            <span class="badge bg-info">{{ $food->category_label }}</span>
        </div>
        
        <div class="mb-4">
            <h4>Mô tả sản phẩm:</h4>
            <p>{{ $food->description ?: 'Chưa có mô tả cho sản phẩm này.' }}</p>
        </div>
        
        <div class="d-grid gap-2 d-md-flex">
            <a href="{{ route('banhang.addtocart', $food->id) }}" class="btn btn-success btn-lg">
                <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
            </a>
            <a href="{{ route('banhang.giohang') }}" class="btn btn-outline-success btn-lg">
                <i class="fas fa-eye me-2"></i>Xem giỏ hàng
            </a>
            @auth
                @if(Auth::user()->level <= 2)
                <a href="{{ route('admin.food.edit', $food->id) }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-edit me-1"></i>Sửa
                </a>
                @endif
            @endauth
        </div>
    </div>
</div>

@if($relatedProducts->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
    </div>
    @foreach($relatedProducts as $product)
    <div class="col-md-3">
        <div class="card product-card">
            <img src="{{ $product->image_url }}" 
                 class="card-img-top product-img" alt="{{ $product->name }}">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <div class="price">
                    @if($product->sale_price)
                        <span class="sale-price">{{ number_format($product->sale_price) }}đ</span>
                    @else
                        <span class="sale-price">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>
                <a href="{{ route('banhang.chitiet', $product->id) }}" class="btn btn-sm btn-outline-primary mt-2 w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection