@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
    <div class="container py-5">
        <div id="content">
            <form action="{{ route('banhang.postdathang') }}" method="post" class="beta-form-checkout">
                @csrf
                <div class="row mb-4">
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Thông tin khách hàng</h4>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên*</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Họ tên" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <div>
                                <label class="me-3"><input type="radio" name="gender" value="nam" checked> Nam</label>
                                <label><input type="radio" name="gender" value="nữ"> Nữ</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ*</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Địa chỉ" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Điện thoại*</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea id="notes" name="notes" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-4 shadow-sm">
                            <h4 class="mb-4">Đơn hàng của bạn</h4>
                            @if(Session::has('cart') && isset($cart))
                                @foreach($productCarts as $product)
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ asset(data_get($product['item'], 'image')) }}" alt="{{ data_get($product['item'], 'name') }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="flex-fill">
                                            <h6 class="mb-1">{{ data_get($product['item'], 'name') }}</h6>
                                            <div class="text-muted">Số lượng: {{ $product['qty'] }}</div>
                                            <div class="text-muted">Thành tiền: {{ number_format($product['price']) }} đ</div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <strong>Tổng tiền:</strong>
                                    <strong>{{ number_format($cart->totalPrice ?? 0) }} đ</strong>
                                </div>
                            @else
                                <div class="alert alert-warning">Giỏ hàng của bạn đang trống.</div>
                            @endif
                            <div class="mb-3">
                                <h5>Hình thức thanh toán</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="COD" checked>
                                    <label class="form-check-label" for="payment_cod">Thanh toán khi nhận hàng</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_atm" value="ATM">
                                    <label class="form-check-label" for="payment_atm">Chuyển khoản</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="VNPAY">
                                    <label class="form-check-label" for="payment_vnpay">Thanh toán online</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
