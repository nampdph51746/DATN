@extends('layouts.client.client')

@section('title', 'Thanh toán thành công')

@section('content')
<div class="container text-center py-5">
    <h2 class="text-success mb-4">🎉 Thanh toán thành công!</h2>
    <p>Cảm ơn bạn đã đặt vé. Đơn hàng của bạn đã được xác nhận và lưu lại trong hệ thống.</p>
    <a href="{{ route('client.home') }}" class="btn btn-primary mt-3">Về trang chủ</a>
</div>
@endsection
