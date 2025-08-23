@extends('layouts.admin.admin')
@section('content')
<div class="container py-5 text-center">
    <h2>QR Sản phẩm</h2>
    <p><strong>Tên sản phẩm:</strong> {{ $item->productVariant?->product?->name }}</p>
    <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
    @if($qrCodeBase64)
        <img src="{{ $qrCodeBase64 }}" alt="QR Sản phẩm" style="max-width:300px;">
        <br>
        <a href="{{ route('admin.foods.print', ['item_id' => $item->id]) }}" class="btn btn-success mt-3" target="_blank">Tải PDF sản phẩm</a>
    @else
        <p class="text-danger">Không tạo được mã QR!</p>
    @endif
</div>
@endsection
