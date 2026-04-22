@extends('admin.master')

@section('title', 'Danh sách danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Danh sách danh mục</h2>
    </div>
    <a href="{{ route('admin.getCateAdd') }}" class="btn btn-success">Thêm danh mục</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Mô tả</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cates as $cate)
                    <tr>
                        <td>{{ $cate->id }}</td>
                        <td>{{ $cate->name }}</td>
                        <td>{{ Str::limit($cate->description, 80) }}</td>
                        <td>{{ $cate->image }}</td>
                        <td>
                            <a href="{{ route('admin.getCateEdit', $cate->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                            <a href="{{ route('admin.getCateDelete', $cate->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có danh mục nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $cates->links() }}</div>
    </div>
</div>
@endsection
