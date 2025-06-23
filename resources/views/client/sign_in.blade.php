@extends('layouts.client.client')
@section('content')
    <div class="container_signup_signin" id="container_signup_signin">
        <div class="form-container sign-up-container">
            <form name="sign-up-form" action="#" onsubmit="return signUpValidateForm()">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input name="sign-up-name" type="text" placeholder="Name" />
                <input name="sign-up-email" type="email" placeholder="Email" />
                <input name="sign-up-passwd" type="password" placeholder="Password" />
                <button>Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form name="sign-in-form" style="color: var(--theme-title);" action="#" onsubmit="return signInValidateForm()">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social" style="color: var(--theme-title);"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social" style="color: var(--theme-title);"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social" style="color: var(--theme-title);"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <input name="sign-in-email" type="email" placeholder="Email" />
                <input name="sign-in-passwd" type="password" placeholder="Password" />
                <a href="#">Forgot your password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your login details</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register and book your tickets now!!!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
@endsection