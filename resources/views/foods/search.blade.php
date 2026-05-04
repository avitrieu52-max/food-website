@extends('layouts.app')

@section('title', 'Tìm kiếm: ' . $keyword)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tìm kiếm</li>
        </ol>
    </nav>

    {{-- Form tìm kiếm --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 p-4">
        <form action="{{ route('banhang.search') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-semibold">Tìm kiếm sản phẩm</label>
                <input type="text" name="q" value="{{ $keyword }}" class="form-control form-control-lg"
                       placeholder="Nhập tên sản phẩm...">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    @if($keyword)
        <h4 class="mb-3">
            Kết quả tìm kiếm cho: <span class="text-success">"{{ $keyword }}"</span>
            <small class="text-muted fs-6">({{ $foods->total() }} sản phẩm)</small>
        </h4>
    @endif

    @if($foods->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-4"></i>
            <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp</h5>
            <a href="{{ route('foods.index') }}" class="btn btn-outline-success mt-3">Xem tất cả sản phẩm</a>
        </div>
    @else
        <div class="row gy-4">
            @foreach($foods as $food)
                <div class="col-xl-3 col-md-4 col-sm-6">
                    @include('foods._product_item', ['food' => $food])
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $foods->links() }}
        </div>
    @endif
</div>
@endsection
