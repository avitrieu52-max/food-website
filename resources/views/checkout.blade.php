@extends('layouts.app')
@section('title', 'Thanh toán')
@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('banhang.postdathang') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- Thông tin khách hàng --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <h4 class="mb-4"><i class="fas fa-user me-2" style="color:#c9a96e;"></i>Thông tin giao hàng</h4>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Giới tính</label>
                        <div>
                            <label class="me-3"><input type="radio" name="gender" value="nam" checked> Nam</label>
                            <label><input type="radio" name="gender" value="nữ"> Nữ</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                        <input type="text" name="address" value="{{ old('address', auth()->user()?->address) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()?->phone) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            {{-- Đơn hàng --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <h4 class="mb-4"><i class="fas fa-shopping-bag me-2" style="color:#c9a96e;"></i>Đơn hàng của bạn</h4>

                    @if(Session::has('cart') && isset($cart) && $cart->totalQty > 0)
                        @foreach($productCarts as $productId => $product)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ isset($product['item']) ? asset($product['item']->image ?? 'images/placeholder.svg') : asset('images/placeholder.svg') }}"
                                 class="rounded me-3" style="width:70px;height:70px;object-fit:cover;">
                            <div class="flex-fill">
                                <div class="fw-semibold">{{ $product['item']->name ?? 'Sản phẩm' }}</div>
                                <div class="text-muted small">SL: {{ $product['qty'] }}</div>
                                <div style="color:#c9a96e; font-weight:600;">{{ number_format($product['price']) }}đ</div>
                            </div>
                        </div>
                        @endforeach

                        <hr>

                        {{-- Mã giảm giá --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="couponInput" class="form-control text-uppercase"
                                       placeholder="Nhập mã giảm giá..."
                                       value="{{ $appliedCoupon['code'] ?? '' }}">
                                <button type="button" class="btn btn-outline-secondary" id="applyCouponBtn">Áp dụng</button>
                                @if($appliedCoupon)
                                <button type="button" class="btn btn-outline-danger" id="removeCouponBtn">Xóa</button>
                                @endif
                            </div>
                            <div id="couponMsg" class="mt-1 small"></div>
                        </div>

                        {{-- Tổng tiền --}}
                        <table class="table table-borderless mb-3">
                            <tr>
                                <td>Tạm tính:</td>
                                <td class="text-end">{{ number_format($cart->totalPrice) }}đ</td>
                            </tr>
                            <tr id="discountRow" style="{{ $discountAmount > 0 ? '' : 'display:none' }}">
                                <td class="text-danger">Giảm giá:</td>
                                <td class="text-end text-danger" id="discountVal">-{{ number_format($discountAmount) }}đ</td>
                            </tr>
                            <tr>
                                <td>Phí vận chuyển:</td>
                                <td class="text-end" id="shippingVal">{{ $shippingFee > 0 ? number_format($shippingFee).'đ' : 'Miễn phí' }}</td>
                            </tr>
                            <tr class="fw-bold fs-5">
                                <td>Tổng thanh toán:</td>
                                <td class="text-end" id="finalTotal" style="color:#c9a96e;">{{ number_format($finalTotal) }}đ</td>
                            </tr>
                        </table>

                        {{-- Phương thức thanh toán --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Phương thức thanh toán</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" value="COD" id="cod" checked>
                                <label class="form-check-label" for="cod"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" value="ATM" id="atm">
                                <label class="form-check-label" for="atm"><i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="VNPAY" id="vnpay">
                                <label class="form-check-label" for="vnpay"><i class="fas fa-credit-card me-2"></i>Thanh toán online (VNPAY)</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5">
                            <i class="fas fa-check-circle me-2"></i>Đặt hàng ngay
                        </button>
                    @else
                        <div class="alert alert-warning">Giỏ hàng của bạn đang trống. <a href="{{ route('foods.index') }}">Tiếp tục mua sắm</a></div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const cartTotal = {{ $cart->totalPrice ?? 0 }};
const shippingFee = {{ $shippingFee ?? 0 }};
let discountAmount = {{ $discountAmount ?? 0 }};

function updateTotals(newDiscount) {
    discountAmount = newDiscount;
    const final = cartTotal + shippingFee - discountAmount;
    if (discountAmount > 0) {
        document.getElementById('discountRow').style.display = '';
        document.getElementById('discountVal').textContent = '-' + new Intl.NumberFormat('vi-VN').format(discountAmount) + 'đ';
    } else {
        document.getElementById('discountRow').style.display = 'none';
    }
    document.getElementById('finalTotal').textContent = new Intl.NumberFormat('vi-VN').format(final) + 'đ';
}

document.getElementById('applyCouponBtn')?.addEventListener('click', function() {
    const code = document.getElementById('couponInput').value.trim();
    if (!code) return;
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({coupon_code: code})
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('couponMsg');
        if (data.success) {
            msg.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Áp dụng thành công! Giảm ' + new Intl.NumberFormat('vi-VN').format(data.discount_amount) + 'đ</span>';
            updateTotals(data.discount_amount);
        } else {
            msg.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>' + data.error + '</span>';
        }
    });
});

document.getElementById('removeCouponBtn')?.addEventListener('click', function() {
    fetch('{{ route("coupon.remove") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    })
    .then(r => r.json())
    .then(() => {
        document.getElementById('couponInput').value = '';
        document.getElementById('couponMsg').innerHTML = '';
        updateTotals(0);
        this.style.display = 'none';
    });
});
</script>
@endpush
@endsection
