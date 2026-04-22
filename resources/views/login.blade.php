@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Đăng nhập</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('flag'))
                        <div class="alert alert-{{ session('flag') }}">{{ session('message') ?? session('thongbao') }}</div>
                    @endif

                    <form action="{{ route('postlogin') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Đăng nhập</button>
                    </form>

                    <div class="mt-3 text-center">
                        Chưa có tài khoản? <a href="{{ route('getsignin') }}">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
