@extends('layouts.admin.admin')
@section('content')
<div class="container py-5 text-center">
    <h2>QR Sản phẩm</h2>
    <p><strong>Tên sản phẩm:</strong> {{ $item->productVariant?->product?->name }}</p>
    <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
    @if($qrCodeBase64)
        <img src="{{ $qrCodeBase64 }}" alt="QR Sản phẩm" style="max-width:300px;">
    @else
        <p class="text-danger">Không tạo được mã QR!</p>
    @endif
</div>
@endsection
