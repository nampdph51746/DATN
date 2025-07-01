@extends('layouts.client.client')
@section('title', 'Quên mật khẩu | CineVN')

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

                                <h2 class="fw-bold fs-24">Quên Mật Khẩu</h2>
                                <p class="text-muted mt-1 mb-4">Nhập email của bạn để nhận liên kết đặt lại mật khẩu.</p>

                                @if (session('status'))
                                    <div class="alert alert-success">{{ session('status') }}</div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-1 text-center d-grid">
                                        <button class="btn btn-soft-primary" type="submit">Gửi Liên Kết Đặt Lại</button>
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