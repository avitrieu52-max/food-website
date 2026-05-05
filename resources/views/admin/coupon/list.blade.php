@extends('admin.master')
@section('title', 'Quản lý Mã giảm giá')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-tag me-2"></i>Quản lý Mã giảm giá</h3>
    <a href="{{ route('admin.coupon.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>Thêm mã</a>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th class="ps-3">Mã</th><th>Loại</th><th>Giá trị</th><th>Đơn tối thiểu</th><th>Đã dùng</th><th>Hết hạn</th><th>Trạng thái</th><th class="text-center">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td class="ps-3"><strong class="text-primary">{{ $coupon->code }}</strong></td>
                    <td>{{ $coupon->discount_type === 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                    <td>{{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : number_format($coupon->discount_value).'đ' }}</td>
                    <td>{{ number_format($coupon->min_order_value) }}đ</td>
                    <td>{{ $coupon->used_count }}{{ $coupon->max_uses ? '/'.$coupon->max_uses : '' }}</td>
                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                    <td>
                        @if($coupon->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tắt</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.coupon.delete', $coupon->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Xóa mã này?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có mã giảm giá nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
