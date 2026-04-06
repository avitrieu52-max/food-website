@extends('layouts.app')

@section('title', "Danh mục $categoryLabel")

@section('content')
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $categoryLabel }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">{{ $categoryLabel }}</h1>
            <p class="text-muted mb-0">Danh sách sản phẩm thuộc danh mục {{ $categoryLabel }}.</p>
        </div>
        <div>
            <a href="{{ route('foods.create') }}" class="btn btn-success">Thêm sản phẩm</a>
        </div>
    </div>

    @if($foods->isEmpty())
        <div class="alert alert-info">Chưa có sản phẩm nào trong danh mục này.</div>
    @else
        <div class="row gy-4">
            @foreach($foods as $food)
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="{{ route('foods.show', $food) }}" class="text-decoration-none text-dark">
                            <img src="{{ $food->image_url ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                 class="card-img-top" alt="{{ $food->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $food->name }}</h5>
                                <p class="card-text text-muted mb-3">{{ \Illuminate\Support\Str::limit($food->description, 70) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($food->sale_price)
                                            <div class="text-danger fw-bold">{{ number_format($food->sale_price) }}đ</div>
                                            <div class="text-muted"><small><s>{{ number_format($food->price) }}đ</s></small></div>
                                        @else
                                            <div class="fw-bold">{{ number_format($food->price) }}đ</div>
                                        @endif
                                    </div>
                                    <span class="badge bg-info">{{ $food->category_label }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
