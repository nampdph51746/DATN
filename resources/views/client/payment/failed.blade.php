@extends('layouts.client.client')

@section('title', 'Thanh toán thất bại')

@section('content')
<div class="container text-center py-5">
    <h2 class="text-danger mb-4">❌ Thanh toán thất bại!</h2>
    <p>Đã xảy ra lỗi trong quá trình thanh toán. Vui lòng thử lại hoặc chọn phương thức khác.</p>
    @if (session('error'))
        <div class="alert alert-warning mt-3">
            {{ session('error') }}
        </div>
    @endif
    <a href="{{ route('client.home') }}" class="btn btn-secondary mt-3">Về trang chủ</a>
</div>
@endsection