@extends('admin.master')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h3>
    <a href="{{ route('admin.food.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Thêm sản phẩm
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.food.list') }}" method="GET" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Tìm kiếm theo tên sản phẩm...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Tìm
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.food.list') }}" class="btn btn-outline-secondary w-100">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Tồn kho</th>
                    <th>Nổi bật</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($foods as $food)
                <tr>
                    <td class="ps-3">{{ $food->id }}</td>
                    <td>
                        <img src="{{ $food->image_url }}" alt="{{ $food->name }}"
                             class="rounded" style="width:50px;height:50px;object-fit:cover;">
                    </td>
                    <td>
                        <strong>{{ $food->name }}</strong>
                    </td>
                    <td><span class="badge bg-secondary">{{ $food->category_label }}</span></td>
                    <td>{{ number_format($food->price) }}đ</td>
                    <td>
                        @if($food->sale_price)
                            <span class="text-danger">{{ number_format($food->sale_price) }}đ</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $food->stock }}</td>
                    <td>
                        @if($food->is_featured)
                            <span class="badge bg-warning text-dark"><i class="fas fa-star"></i></span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($food->status)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.food.edit', $food->id) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('admin.food.delete', $food->id) }}" class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa sản phẩm này?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Không có sản phẩm nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $foods->links() }}</div>
@endsection
