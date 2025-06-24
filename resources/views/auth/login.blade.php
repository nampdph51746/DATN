@extends('auth.layouts.auth')
@section('title', 'Đăng nhập | CineVN')

@section('content')
    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">

                                <div class="auth-logo mb-4">
                                    <a href="{{ url('/') }}" class="logo-dark">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" height="24" alt="logo dark">
                                    </a>
                                    <a href="{{ url('/') }}" class="logo-light">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" height="24" alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Đăng Nhập</h2>
                                <p class="text-muted mt-1 mb-4">Nhập email và mật khẩu để truy cập hệ thống quản trị.</p>

                                <!-- Thông báo trạng thái -->
                                @if (session('status'))
                                    <div class="alert alert-success">{{ session('status') }}</div>
                                @endif

                                <!-- Form đăng nhập -->
                                <form method="POST" action="{{ route('login') }}" class="authentication-form">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <a href="{{ route('password.request') }}" class="float-end text-muted text-unline-dashed ms-1">Quên mật khẩu?</a>
                                        <label class="form-label" for="password">Mật khẩu</label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Ghi nhớ đăng nhập -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                            <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                                        </div>
                                    </div>

                                    <!-- Nút đăng nhập -->
                                    <div class="mb-1 text-center d-grid">
                                        <button class="btn btn-soft-primary" type="submit">Đăng Nhập</button>
                                    </div>
                                </form>

                                <p class="mt-3 fw-semibold no-span">Hoặc đăng nhập với</p>
                                <div class="d-grid gap-2">
                                    <a href="#" class="btn btn-soft-dark"><i class="bx bxl-google fs-20 me-1"></i> Google</a>
                                    <a href="#" class="btn btn-soft-primary"><i class="bx bxl-facebook fs-20 me-1"></i> Facebook</a>
                                </div>

                                <p class="text-danger text-center mt-4">
                                    Chưa có tài khoản? 
                                    <a href="{{ route('register') }}" class="text-dark fw-bold ms-1">Đăng Ký</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ảnh nền bên phải -->
                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <div class="d-flex flex-column h-100">
                            <img src="{{ asset('assets/images/small/img-11.jpg') }}" alt="" class="w-100 h-100">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

