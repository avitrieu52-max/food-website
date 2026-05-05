@extends('admin.master')
@section('title', 'Phí vận chuyển')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-truck me-2"></i>Quản lý Phí vận chuyển</h3>
    <a href="{{ route('admin.shipping.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>Thêm quy tắc</a>
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
                <tr><th class="ps-3">Tên quy tắc</th><th>Đơn hàng tối thiểu</th><th>Phí vận chuyển</th><th>Trạng thái</th><th class="text-center">Thao tác</th></tr>
            </thead>
            <tbody>
                @foreach($fees as $fee)
                <tr>
                    <td class="ps-3"><strong>{{ $fee->name }}</strong></td>
                    <td>{{ number_format($fee->min_order_value) }}đ</td>
                    <td>{{ $fee->fee > 0 ? number_format($fee->fee).'đ' : '<span class="text-success fw-bold">Miễn phí</span>' }}</td>
                    <td>
                        @if($fee->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tắt</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.shipping.edit', $fee->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.shipping.delete', $fee->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Xóa quy tắc này?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
