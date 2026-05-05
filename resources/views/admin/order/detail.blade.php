@extends('admin.master')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Chi tiết đơn hàng #{{ $order->id }}</h3>
    <a href="{{ route('admin.order.list') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white fw-bold">Thông tin khách hàng</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Họ tên:</th><td>{{ $order->customer->name ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Giới tính:</th><td>{{ $order->customer->gender ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Email:</th><td>{{ $order->customer->email ?? '-' }}</td></tr>
                    <tr><th class="text-muted">SĐT:</th><td>{{ $order->customer->phone_number ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Địa chỉ:</th><td>{{ $order->customer->address ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Ghi chú:</th><td>{{ $order->note ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white fw-bold">Thông tin đơn hàng</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Mã đơn:</th><td>#{{ $order->id }}</td></tr>
                    <tr><th class="text-muted">Ngày đặt:</th><td>{{ $order->date_order }}</td></tr>
                    <tr><th class="text-muted">Thanh toán:</th><td><span class="badge bg-secondary">{{ $order->payment }}</span></td></tr>
                    <tr>
                        <th class="text-muted">Trạng thái:</th>
                        <td>
                            @php $sl = $order->status_label; @endphp
                            <span class="badge bg-{{ $sl['class'] }}">{{ $sl['label'] }}</span>
                        </td>
                    </tr>
                    <tr><th class="text-muted">Tổng tiền:</th><td class="text-success fw-bold fs-5">{{ number_format($order->total) }}đ</td></tr>
                </table>

                <hr>
                <h6 class="fw-bold mb-3">Cập nhật trạng thái</h6>
                <form action="{{ route('admin.order.status', $order->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <select name="status" class="form-select">
                        @foreach(\App\Models\Bill::statusLabels() as $key => $info)
                            <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary text-nowrap">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold">Sản phẩm trong đơn</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if($detail->food)
                                        <img src="{{ $detail->food->image_url }}" alt="{{ $detail->food->name }}"
                                             class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                        <span>{{ $detail->food->name }}</span>
                                    @else
                                        <span class="text-muted">Sản phẩm #{{ $detail->id_product }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">{{ number_format($detail->unit_price) }}đ</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-center fw-semibold text-success">{{ number_format($detail->unit_price * $detail->quantity) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold ps-3">Tạm tính:</td>
                            <td class="text-center fw-bold">{{ number_format($order->total - $order->shipping_fee + $order->discount_amount) }}đ</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="text-end text-danger ps-3">Giảm giá ({{ $order->coupon_code }}):</td>
                            <td class="text-center text-danger">-{{ number_format($order->discount_amount) }}đ</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end ps-3">Phí vận chuyển:</td>
                            <td class="text-center">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee).'đ' : 'Miễn phí' }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold ps-3">Tổng thanh toán:</td>
                            <td class="text-center fw-bold text-success fs-5">{{ number_format($order->total) }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
