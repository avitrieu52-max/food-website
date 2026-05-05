@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng #' . $order->id)
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.index') }}" class="text-decoration-none">Tài khoản</a></li>
            <li class="breadcrumb-item active">Đơn hàng #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold">Thông tin giao hàng</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th class="text-muted" style="width:40%">Họ tên:</th><td>{{ $order->customer->name ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Email:</th><td>{{ $order->customer->email ?? '-' }}</td></tr>
                        <tr><th class="text-muted">SĐT:</th><td>{{ $order->customer->phone_number ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Địa chỉ:</th><td>{{ $order->customer->address ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Ghi chú:</th><td>{{ $order->note ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold">Thông tin đơn hàng</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th class="text-muted" style="width:40%">Mã đơn:</th><td><strong>#{{ $order->id }}</strong></td></tr>
                        <tr><th class="text-muted">Ngày đặt:</th><td>{{ $order->date_order }}</td></tr>
                        <tr><th class="text-muted">Thanh toán:</th><td>{{ $order->payment }}</td></tr>
                        <tr><th class="text-muted">Trạng thái:</th>
                            <td>@php $sl = $order->status_label; @endphp<span class="badge bg-{{ $sl['class'] }}">{{ $sl['label'] }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold">Sản phẩm đã đặt</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr><th class="ps-3">Sản phẩm</th><th class="text-center">Đơn giá</th><th class="text-center">SL</th><th class="text-center">Thành tiền</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($detail->food)
                                            <img src="{{ $detail->food->image_url }}" class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                            <span>{{ $detail->food->name }}</span>
                                        @else
                                            <span class="text-muted">Sản phẩm #{{ $detail->id_product }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ number_format($detail->unit_price) }}đ</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-center fw-semibold">{{ number_format($detail->unit_price * $detail->quantity) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            @php $subtotal = $order->total - $order->shipping_fee + $order->discount_amount; @endphp
                            <tr><td colspan="3" class="text-end">Tạm tính:</td><td class="text-center">{{ number_format($subtotal) }}đ</td></tr>
                            @if($order->discount_amount > 0)
                            <tr><td colspan="3" class="text-end text-danger">Giảm giá ({{ $order->coupon_code }}):</td><td class="text-center text-danger">-{{ number_format($order->discount_amount) }}đ</td></tr>
                            @endif
                            <tr><td colspan="3" class="text-end">Phí vận chuyển:</td><td class="text-center">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee).'đ' : 'Miễn phí' }}</td></tr>
                            <tr><td colspan="3" class="text-end fw-bold">Tổng thanh toán:</td><td class="text-center fw-bold fs-5" style="color:#c9a96e;">{{ number_format($order->total) }}đ</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('account.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại tài khoản</a>
    </div>
</div>
@endsection
