@component('mail::message')
# 🔐 Xin chào {{ $user->name ?? 'bạn' }}!

Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản **CineVN** của bạn.

Vui lòng nhấn nút bên dưới để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau **60 phút**.

@component('mail::button', ['url' => $url])
Đặt lại mật khẩu
@endcomponent

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.

Trân trọng,<br>
**Đội ngũ CineVN**

@slot('subcopy')
Nếu bạn gặp sự cố khi nhấn nút "Đặt lại mật khẩu", hãy sao chép và dán liên kết này vào trình duyệt của bạn:  
[{{ $url }}]({{ $url }})
@endslot
@endcomponent
