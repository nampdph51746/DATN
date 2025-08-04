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
