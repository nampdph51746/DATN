@extends('layouts.client.client')
<!-- ..............Đăng nhập / Đăng ký............... -->
<link rel="stylesheet" type="text/css" href="client_assets/assets/css/sign-in.css">
@section('content')
<div class="container_signup_signin" id="container_signup_signin">
    <div class="form-container sign-up-container">
        <form name="sign-up-form" action="#" onsubmit="return signUpValidateForm()">
            <h1>Tạo tài khoản</h1>
            <div class="social-container">
                <a href="#" class="social" title="Đăng ký bằng Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social" title="Đăng ký bằng Google"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social" title="Đăng ký bằng LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>hoặc đăng ký bằng email</span>
            <input name="sign-up-name" type="text" placeholder="Nhập họ và tên" />
            <input name="sign-up-email" type="email" placeholder="Nhập địa chỉ email" />
            <input name="sign-up-passwd" type="password" placeholder="Nhập mật khẩu" />
            <button>Đăng ký</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form name="sign-in-form" style="color: var(--theme-title);" action="#" onsubmit="return signInValidateForm()">
            <h1>Đăng nhập</h1>
            <div class="social-container">
                <a href="#" class="social" style="color: var(--theme-title);" title="Đăng nhập bằng Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social" style="color: var(--theme-title);" title="Đăng nhập bằng Google"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social" style="color: var(--theme-title);" title="Đăng nhập bằng LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>hoặc đăng nhập bằng email</span>
            <input name="sign-in-email" type="email" placeholder="Nhập địa chỉ email" />
            <input name="sign-in-passwd" type="password" placeholder="Nhập mật khẩu" />
            <a href="#">Quên mật khẩu?</a>
            <button>Đăng nhập</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Chào mừng trở lại!</h1>
                <p>Để tiếp tục kết nối với chúng tôi, vui lòng đăng nhập bằng thông tin của bạn</p>
                <button class="ghost" id="signIn">Đăng nhập</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Xin chào, bạn mới!</h1>
                <p>Hãy đăng ký và đặt vé ngay hôm nay!</p>
                <button class="ghost" id="signUp">Đăng ký</button>
            </div>
        </div>
    </div>
</div>
@include('client.footer.footer')
@endsection