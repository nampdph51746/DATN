@extends('layouts.client.client')

@section('title', 'Thanh toán thất bại')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h4 class="text-danger text-center mb-3">❌ Thanh toán thất bại</h4>

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @else
            <div class="alert alert-danger text-center">
                Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại!
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('checkout.preview') }}" class="btn btn-outline-primary">
                🔁 Quay lại thanh toán
            </a>
        </div>
    </div>
</div>
@endsection
