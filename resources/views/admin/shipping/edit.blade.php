@extends('admin.master')
@section('title', 'Sửa Phí vận chuyển')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Sửa quy tắc phí vận chuyển</h3>
    <a href="{{ route('admin.shipping.list') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.shipping.update', $fee->id) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên quy tắc <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $fee->name) }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Đơn hàng tối thiểu (đ) <span class="text-danger">*</span></label>
                    <input type="number" name="min_order_value" value="{{ old('min_order_value', $fee->min_order_value) }}" class="form-control" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Phí vận chuyển (đ) <span class="text-danger">*</span></label>
                    <input type="number" name="fee" value="{{ old('fee', $fee->fee) }}" class="form-control" min="0" required>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $fee->is_active) ? 'checked' : '' }}>
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
