@extends('layouts.app')
@section('title', $category->name)
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foods.index') }}" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $category->name }}</h2>
            @if($category->description)
                <p class="text-muted mb-0">{{ $category->description }}</p>
            @endif
        </div>
        <span class="badge bg-secondary fs-6">{{ $foods->count() }} sản phẩm</span>
    </div>

    @if($foods->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có sản phẩm nào trong danh mục này</h5>
            <a href="{{ route('foods.index') }}" class="btn btn-success mt-3">Xem tất cả sản phẩm</a>
        </div>
    @else
        <div class="row gy-4">
            @foreach($foods as $food)
                <div class="col-xl-3 col-md-4 col-sm-6">
                    @include('foods._product_item', ['food' => $food])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
