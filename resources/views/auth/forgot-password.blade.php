@extends('auth.layouts.auth')
@section('title', 'Quên mật khẩu | CineVN')

@section('content')

                <div class="d-flex flex-column h-100 p-3">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <div class="auth-logo mb-4">
                                    <a href="index.html" class="logo-dark">
                                        <img src="assets/images/logo-dark.png" height="24" alt="logo dark">
                                    </a>
                                    <a href="index.html" class="logo-light">
                                        <img src="assets/images/logo-light.png" height="24" alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Đặt Lại Mật Khẩu</h2>

                                <p class="text-muted mt-1 mb-4">Nhập địa chỉ email của bạn, chúng tôi sẽ gửi cho bạn một email với hướng dẫn đặt lại mật khẩu.</p>

                                <div>
                                    <form action="{{ route('password.email') }}" class="authentication-form" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="email" value="{{ old('email') }}">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email của bạn">
                                        </div>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <div class="mb-1 text-center d-grid">
                                            <button class="btn btn-primary" type="submit">Gửi Yêu Cầu</button>
                                        </div>
                                    </form>
                                </div>

                                <p class="mt-5 text-danger text-center">
                                    Quay lại <a href="{{ route('login') }}" class="text-dark fw-bold ms-1">Đăng Nhập</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <div class="d-flex flex-column h-100">
                            <img src="assets/images/small/img-10.jpg" alt="" class="w-100 h-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection