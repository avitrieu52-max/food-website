@extends('layouts.app')
@section('title', 'Tài khoản của tôi')
@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-user-circle me-2" style="color:#c9a96e;"></i>Tài khoản của tôi</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('password_error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('password_error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <ul class="nav nav-tabs mb-4" id="accountTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#orders"><i class="fas fa-shopping-bag me-1"></i>Lịch sử đơn hàng</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#profile"><i class="fas fa-user me-1"></i>Thông tin cá nhân</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#password"><i class="fas fa-lock me-1"></i>Đổi mật khẩu</a></li>
    </ul>

    <div class="tab-content">
        {{-- Tab: Lịch sử đơn hàng --}}
        <div class="tab-pane fade show active" id="orders">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr><th class="ps-3">Mã đơn</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th><th class="text-center">Chi tiết</th></tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="ps-3"><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->date_order }}</td>
                                <td class="fw-semibold" style="color:#c9a96e;">{{ number_format($order->total) }}đ</td>
                                <td>
                                    @php $sl = $order->status_label; @endphp
                                    <span class="badge bg-{{ $sl['class'] }}">{{ $sl['label'] }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('account.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Bạn chưa có đơn hàng nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab: Thông tin cá nhân --}}
        <div class="tab-pane fade" id="profile">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('account.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" value="{{ $user->email }}" class="form-control" readonly disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" maxlength="20">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Địa chỉ</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control" maxlength="500">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success px-5"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tab: Đổi mật khẩu --}}
        <div class="tab-pane fade" id="password">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('account.password') }}" method="POST">
                        @csrf
                        <div class="row g-3" style="max-width:500px;">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" required minlength="6">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning px-5"><i class="fas fa-key me-2"></i>Đổi mật khẩu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
