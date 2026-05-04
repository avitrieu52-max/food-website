@extends('admin.master')

@section('title', 'Thêm người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Thêm người dùng mới</h3>
    <a href="{{ route('admin.user.list') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                    <select name="level" class="form-select" required>
                        <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('level') == 2 ? 'selected' : '' }}>Nhân viên</option>
                        <option value="3" {{ old('level', 3) == 3 ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-success px-5">
                        <i class="fas fa-save me-2"></i>Lưu người dùng
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
