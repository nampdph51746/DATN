<<<<<<< Updated upstream
@extends('layouts.client.client')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-indigo-200 py-12 px-4">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-indigo-50 rounded-full p-3 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6s-2.5 1.12-2.5 2.5S10.62 11 12 11zm0 2c-1.67 0-5 0.84-5 2.5V17h10v-1.5c0-1.66-3.33-2.5-5-2.5z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-indigo-700 text-center">Đặt Lại Mật Khẩu</h2>
            <p class="text-gray-500 text-center mt-2">Nhập mật khẩu mới để truy cập tài khoản của bạn</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                       placeholder="Nhập email của bạn" value="{{ old('email', $request->email) }}" required autofocus>
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Mật khẩu mới</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                       placeholder="Nhập mật khẩu mới" required>
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                       placeholder="Nhập lại mật khẩu" required>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold shadow-lg">
                <span class="inline-flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Đặt Lại Mật Khẩu
                </span>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-semibold text-sm">&larr; Quay lại Đăng nhập</a>
        </div>
    </div>
</div>

@endsection
=======
<div style="max-width: 400px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align:center; font-weight:700; color:#2d3748; margin-bottom:24px;">Đặt lại mật khẩu</h2>
    <form method="POST" action="{{ route('password.reset.submit') }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div style="margin-bottom:18px;">
            <label for="email" style="display:block; font-weight:500; color:#4a5568; margin-bottom:6px;">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', request()->email) }}"
                required
                readonly
                style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; background:#f1f5f9; color:#64748b; font-size:1rem; cursor:not-allowed;"
            >
        </div>

        <div style="margin-bottom:18px;">
            <label for="password" style="display:block; font-weight:500; color:#4a5568; margin-bottom:6px;">Mật khẩu mới</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Nhập mật khẩu mới"
                required
                style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc; font-size:1rem;"
            >
        </div>

        <div style="margin-bottom:24px;">
            <label for="password_confirmation" style="display:block; font-weight:500; color:#4a5568; margin-bottom:6px;">Xác nhận mật khẩu</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Nhập lại mật khẩu"
                required
                style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc; font-size:1rem;"
            >
        </div>

        <button type="submit"
            style="width:100%; background:#000000; color:#fff; font-weight:600; padding:12px 0; border:none; border-radius:6px; font-size:1.1rem; transition:background 0.2s;">
            Đặt lại mật khẩu
        </button>
    </form>
</div>
>>>>>>> Stashed changes
