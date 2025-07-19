@extends('layouts.admin.admin')

@section('content')
<div class="container">
    <h1>Chi tiết phương thức thanh toán</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $paymentMethod->id }}</p>
            <p><strong>Tên:</strong> {{ $paymentMethod->name }}</p>
            <p><strong>Code:</strong> {{ $paymentMethod->code }}</p>
            <p><strong>Kích hoạt:</strong> 
                @if($paymentMethod->is_active)
                    <span class="badge bg-success">Đang hoạt động</span>
                @else
                    <span class="badge bg-secondary">Không hoạt động</span>
                @endif
            </p>
            <p><strong>Logo:</strong><br>
                @if($paymentMethod->logo_url)
                    <img src="{{ $paymentMethod->logo_url }}" alt="{{ $paymentMethod->name }}" style="max-height: 100px;">
                @else
                    Không có logo
                @endif
            </p>
            <a href="{{ route('payment_methods.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection
