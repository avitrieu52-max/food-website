@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white rounded-3 p-3 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2 text-success"></i>Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(empty($productCarts))
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h4 class="text-muted">Giỏ hàng của bạn đang trống</h4>
            <a href="{{ route('foods.index') }}" class="btn btn-success mt-3">
                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Thành tiền</th>
                                    <th class="text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productCarts as $id => $item)
                                    @php
                                        $product = $item['item'];
                                        $unitPrice = $item['qty'] > 0 ? $item['price'] / $item['qty'] : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset(is_object($product) ? $product->image : data_get($product, 'image', '')) }}"
                                                     alt="{{ is_object($product) ? $product->name : data_get($product, 'name') }}"
                                                     class="rounded" style="width:70px;height:70px;object-fit:cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ is_object($product) ? $product->name : data_get($product, 'name') }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center text-success fw-semibold">
                                            {{ number_format($unitPrice) }}đ
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('banhang.updatecart', $id) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                                @csrf
                                                <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}" class="btn btn-outline-secondary btn-sm px-2">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="99"
                                                       class="form-control form-control-sm text-center" style="width:55px;"
                                                       onchange="this.form.submit()">
                                                <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="btn btn-outline-secondary btn-sm px-2">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center fw-bold text-success">
                                            {{ number_format($item['price']) }}đ
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('banhang.xoagiohang', $id) }}"
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('foods.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Số lượng sản phẩm:</span>
                            <span>{{ $cart->totalQty }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng tiền:</strong>
                            <strong class="text-success fs-5">{{ number_format($cart->totalPrice) }}đ</strong>
                        </div>
                        <a href="{{ route('banhang.getdathang') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-credit-card me-2"></i>Tiến hành đặt hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
