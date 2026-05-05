@extends('admin.master')
@section('title', 'Quản lý Slide')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-images me-2"></i>Quản lý Slide/Banner</h3>
    <a href="{{ route('admin.slide.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>Thêm slide</a>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th class="ps-3">#</th><th>Ảnh</th><th>Tiêu đề</th><th>Thứ tự</th><th>Trạng thái</th><th class="text-center">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse($slides as $slide)
                <tr>
                    <td class="ps-3">{{ $slide->id }}</td>
                    <td><img src="{{ $slide->image_url }}" class="rounded" style="width:80px;height:45px;object-fit:cover;"></td>
                    <td><strong>{{ $slide->title }}</strong><br><small class="text-muted">{{ $slide->subtitle }}</small></td>
                    <td>{{ $slide->order }}</td>
                    <td>
                        @if($slide->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.slide.edit', $slide->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.slide.delete', $slide->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Xóa slide này?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có slide nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $slides->links() }}</div>
@endsection
