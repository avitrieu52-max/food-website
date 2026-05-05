@extends('admin.master')
@section('title', 'Sửa Mã giảm giá')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Sửa Mã giảm giá</h3>
    <a href="{{ route('admin.coupon.list') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-control text-uppercase" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Loại giảm giá <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percent" {{ old('discount_type', $coupon->discount_type)=='percent'?'selected':'' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type)=='fixed'?'selected':'' }}>Số tiền cố định (đ)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" class="form-control" min="0" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Đơn hàng tối thiểu (đ)</label>
                    <input type="number" name="min_order_value" value="{{ old('min_order_value', $coupon->min_order_value) }}" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Số lần dùng tối đa</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" class="form-control" min="1" placeholder="Để trống = không giới hạn">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ngày hết hạn</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-warning px-5"><i class="fas fa-save me-2"></i>Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
