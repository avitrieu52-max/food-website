@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container-fluid my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Quản lý sản phẩm</h1>
            <p class="text-muted mb-0">Tổng cộng: {{ $foods->count() }} sản phẩm</p>
        </div>
        <a href="{{ route('foods.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($foods->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Chưa có sản phẩm nào. <a href="{{ route('foods.create') }}">Thêm sản phẩm ngay</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 150px;">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 120px;">Giá</th>
                        <th style="width: 100px;">Giá KM</th>
                        <th style="width: 80px;">Kho</th>
                        <th style="width: 100px;">Danh mục</th>
                        <th style="width: 80px;">Nổi bật</th>
                        <th style="width: 80px;">Trạng thái</th>
                        <th style="width: 180px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foods as $food)
                    <tr>
                        <td><strong>#{{ $food->id }}</strong></td>
                        <td>
                            <img src="{{ $food->image_url }}" alt="{{ $food->name }}" style="max-width: 80px; border-radius: 0.35rem;">
                        </td>
                        <td>
                            <a href="{{ route('foods.show', $food) }}" class="text-decoration-none fw-500">
                                {{ Str::limit($food->name, 30) }}
                            </a>
                        </td>
                        <td>{{ number_format($food->price) }}đ</td>
                        <td>
                            @if($food->sale_price)
                                <span class="text-danger fw-bold">{{ number_format($food->sale_price) }}đ</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge @if($food->stock > 0) bg-success @else bg-danger @endif">
                                {{ $food->stock }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $food->category_label }}</span>
                        </td>
                        <td>
                            <i class="fas @if($food->is_featured) fa-star text-warning @else fa-star-o text-muted @endif"></i>
                        </td>
                        <td>
                            @if($food->status)
                                <i class="fas fa-check-circle text-success" title="Hiển thị"></i>
                            @else
                                <i class="fas fa-times-circle text-danger" title="Ẩn"></i>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('foods.show', $food) }}" class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('foods.edit', $food) }}" class="btn btn-outline-warning" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Xóa" data-food-id="{{ $food->id }}" onclick="deleteFood(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<form id="deleteForm" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteFood(btn) {
    const id = btn.getAttribute('data-food-id');
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        const form = document.getElementById('deleteForm');
        form.action = '/foods/' + id;
        form.submit();
    }
}
</script>
@endsection
