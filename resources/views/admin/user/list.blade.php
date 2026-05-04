@extends('admin.master')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-users me-2"></i>Quản lý người dùng</h3>
    <a href="{{ route('admin.user.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>Thêm người dùng
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.user.list') }}" method="GET" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Tìm theo tên hoặc email...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Tìm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.user.list') }}" class="btn btn-outline-secondary w-100">Xóa lọc</a>
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
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-3">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @if($user->level == 1)
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->level == 2)
                            <span class="badge bg-warning text-dark">Nhân viên</span>
                        @else
                            <span class="badge bg-info">Khách hàng</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <a href="{{ route('admin.user.delete', $user->id) }}" class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa người dùng {{ $user->name }}?')">
                            <i class="fas fa-trash"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Không có người dùng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
