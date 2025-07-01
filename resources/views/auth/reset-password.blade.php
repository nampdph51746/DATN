@extends('layouts.client.client')
@section('title', 'Đặt lại mật khẩu | CineVN')

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

                                <h2 class="fw-bold fs-24">Đặt Lại Mật Khẩu</h2>
                                <p class="text-muted mt-1 mb-4">Nhập mật khẩu mới cho tài khoản của bạn.</p>

                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Nhập địa chỉ email" value="{{ old('email', $request->email) }}" required autofocus>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu mới</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới" required>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                                    </div>

                                    <div class="mb-1 text-center d-grid">
                                        <button class="btn btn-soft-primary" type="submit">Đặt Lại Mật Khẩu</button>
                                    </div>
                                </form>

                                <p class="mt-4 text-center">
                                    <a href="{{ route('login') }}" class="text-dark fw-bold">Quay lại Đăng Nhập</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <img src="{{ asset('assets/images/small/img-11.jpg') }}" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection