@extends('auth.layouts.auth')
@section('content')
<div class="d-flex flex-column h-100 p-3">
    <div class="d-flex flex-column flex-grow-1">
        <div class="row h-100">
            <div class="col-xxl-7">
                <div class="row justify-content-center h-100">
                    <div class="col-lg-6 py-lg-5">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="auth-logo mb-4">
                                <a href="/" class="logo-dark"><img src="{{ asset('assets/images/logo-dark.png') }}"
                                        height="24" alt="logo dark"></a>
                                <a href="/" class="logo-light"><img
                                        src="{{ asset('assets/images/logo-light.png') }}" height="24"
                                        alt="logo light"></a>
                            </div>

                            <h2 class="fw-bold fs-24">Đăng ký tài khoản</h2>
                            <p class="text-muted mt-1 mb-4">Lần đầu sử dụng CineVN? Hãy tạo tài khoản ngay!</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="name">Họ và tên</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Nhập họ tên của bạn" value="{{ old('name') }}">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Nhập địa chỉ email" value="{{ old('email') }}">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="phone_number">Số điện thoại</label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                                        placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="date_of_birth">Ngày sinh</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                        value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Nhập mật khẩu">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Nhập lại mật khẩu">
                                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-1 text-center d-grid">
                                    <button class="btn" type="submit" 
                                        style="background-color: #007bff; color: #fff; font-weight: bold;">
                                        Tạo tài khoản
                                    </button>
                                </div>
                            </form>

                            <p class="mt-3 fw-semibold no-span">Hoặc đăng ký với</p>
                            <div class="d-grid gap-2">
                                <a href="#" class="btn" style="background-color: #db4437; color: #fff;">
                                    <i class="bx bxl-google fs-20 me-1"></i> Google
                                </a>
                                <a href="#" class="btn" style="background-color: #4267B2; color: #fff;">
                                    <i class="bx bxl-facebook fs-20 me-1"></i> Facebook
                                </a>
                            </div>

                            <p class="mt-auto text-danger text-center">
                                Đã có tài khoản?
                                <a href="{{ route('login') }}" class="fw-bold ms-1" style="color: #007bff;">Đăng nhập</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-5 d-none d-xxl-flex">
                <div class="card h-100 mb-0 overflow-hidden">
                    <img src="{{ asset('assets/images/small/img-11.jpg') }}" alt="Ảnh nền" class="w-100 h-100">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
