@extends('layouts.client.client')


@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-indigo-200 py-12 px-4">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-indigo-50 rounded-full p-3 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12v1m0 4v1m-8-5v1m0 4v1m8-9V7a4 4 0 00-8 0v2a4 4 0 008 0z" /></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-indigo-700 text-center">Quên Mật Khẩu</h2>
            <p class="text-gray-500 text-center mt-2">Nhập email để nhận liên kết đặt lại mật khẩu</p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded mb-4 text-center text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" placeholder="Nhập email của bạn"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-200 bg-gray-50" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold shadow-lg">
                <span class="inline-flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    Gửi Liên Kết Đặt Lại
                </span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-semibold text-sm">&larr; Quay lại Đăng nhập</a>
        </div>
    </div>
</div>

@endsection

