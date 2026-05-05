@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-tshirt fa-2x mb-2" style="color:#c9a96e;"></i>
                        <h3 class="fw-bold">Đăng nhập</h3>
                        <p class="text-muted small">Admin và khách hàng dùng chung trang này</p>
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

                    @if(session('flag'))
                        <div class="alert alert-{{ session('flag') }}">{{ session('message') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('postlogin') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="example@gmail.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Nhập mật khẩu" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        Chưa có tài khoản?
                        <a href="{{ route('getsignin') }}" style="color:#c9a96e;">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
