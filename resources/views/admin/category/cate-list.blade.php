@extends('admin.master')
@section('title', 'Quản lý Loại sản phẩm')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-tags me-2"></i>Quản lý Loại sản phẩm</h3>
    <a href="{{ route('admin.getCateAdd') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>Thêm loại</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th class="text-center">Số SP</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cates as $cate)
                <tr>
                    <td class="ps-3">{{ $cate->id }}</td>
                    <td>
                        @if($cate->image)
                            <img src="{{ asset($cate->image) }}" class="rounded" style="width:50px;height:50px;object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                <i class="fas fa-tags text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $cate->name }}</strong></td>
                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($cate->description, 60) }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary">{{ $cate->foods_count }} SP</span>
                    </td>
                    <td class="text-center">
                        @if($cate->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.getCateEdit', $cate->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.getCateDelete', $cate->id) }}" class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa danh mục {{ $cate->name }}?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $cates->links() }}</div>
@endsection
