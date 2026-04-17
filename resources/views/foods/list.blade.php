@extends('layouts.app')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tất cả sản phẩm</li>
        </ol>
    </nav>

    <div class="row mb-5">
        {{-- Sidebar Bộ lọc --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
                <h5 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i>Danh mục</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('foods.index', request()->except('category')) }}" 
                           class="text-decoration-none {{ !request('category') ? 'text-success fw-bold' : 'text-dark' }}">
                            Tất cả sản phẩm
                        </a>
                    </li>
                    @foreach($categories as $key => $label)
                        <li class="mb-2">
                            <a href="{{ route('foods.index', array_merge(request()->query(), ['category' => $key])) }}" 
                               class="text-decoration-none {{ request('category') == $key ? 'text-success fw-bold' : 'text-dark' }}">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h1 class="h3 mb-0">Kết quả tìm kiếm</h1>
                    <p class="text-muted mb-0">Hiển thị {{ $foods->count() }} sản phẩm</p>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="text-nowrap text-muted small">Sắp xếp:</span>
                    <select class="form-select w-auto shadow-sm border-0" onchange="location = this.value;">
                        <option value="{{ route('foods.index', array_merge(request()->query(), ['sort' => 'newest'])) }}" 
                            {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="{{ route('foods.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" 
                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="{{ route('foods.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" 
                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                </div>
            </div>

            @if($foods->isEmpty())
                <div class="alert alert-info border-0 shadow-sm">Không tìm thấy sản phẩm nào phù hợp với yêu cầu của bạn.</div>
            @else
                <div class="row gy-4">
                    @foreach($foods as $food)
                        <div class="col-xl-4 col-md-6">
                            @include('foods._product_item', ['food' => $food])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $foods->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection