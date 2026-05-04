@extends('admin.master')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Quản lý đơn hàng</h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.order.list') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       placeholder="Tìm theo tên hoặc SĐT khách hàng...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach(\App\Models\Bill::statusLabels() as $key => $info)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Lọc</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.order.list') }}" class="btn btn-outline-secondary w-100">Xóa lọc</a>
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
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-3">{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->customer->phone_number ?? '-' }}</td>
                    <td>{{ $order->date_order }}</td>
                    <td class="text-success fw-semibold">{{ number_format($order->total) }}đ</td>
                    <td><span class="badge bg-secondary">{{ $order->payment }}</span></td>
                    <td>
                        @php $sl = $order->status_label; @endphp
                        <span class="badge bg-{{ $sl['class'] }}">{{ $sl['label'] }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.order.delete', $order->id) }}" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xóa đơn hàng #{{ $order->id }}?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Không có đơn hàng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
