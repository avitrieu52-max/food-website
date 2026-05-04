@extends('admin.master')

@section('title', 'Dashboard - Admin')

@section('content')
<h3 class="mb-4">Dashboard</h3>

<div class="row gy-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 bg-primary text-white rounded-3">
            <i class="fas fa-shopping-bag fa-2x mb-2"></i>
            <h2 class="fw-bold">{{ $totalOrders }}</h2>
            <p class="mb-0">Đơn hàng</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 bg-success text-white rounded-3">
            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
            <h2 class="fw-bold">{{ number_format($totalRevenue) }}đ</h2>
            <p class="mb-0">Doanh thu</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 bg-warning text-white rounded-3">
            <i class="fas fa-box fa-2x mb-2"></i>
            <h2 class="fw-bold">{{ $totalProducts }}</h2>
            <p class="mb-0">Sản phẩm</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 bg-info text-white rounded-3">
            <i class="fas fa-users fa-2x mb-2"></i>
            <h2 class="fw-bold">{{ $totalUsers }}</h2>
            <p class="mb-0">Người dùng</p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white fw-bold">Đơn hàng gần đây</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td class="ps-3">{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->date_order }}</td>
                    <td class="text-success fw-semibold">{{ number_format($order->total) }}đ</td>
                    <td>
                        @php $sl = $order->status_label; @endphp
                        <span class="badge bg-{{ $sl['class'] }}">{{ $sl['label'] }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
