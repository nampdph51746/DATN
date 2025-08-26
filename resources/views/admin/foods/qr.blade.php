@extends('layouts.admin.admin')
@section('content')
<div class="container py-5 text-center">
    @if($item->combo_id)
        <!-- Đây là combo -->
        <h2>QR Combo</h2>
        <div class="mb-4">
            <h4 class="text-primary">🍿 {{ $item->combo->name }}</h4>
            <p><strong>Số lượng combo:</strong> {{ $item->quantity }}</p>
            
            <!-- Hiển thị chi tiết combo -->
            <div class="card mx-auto" style="max-width: 500px;">
                <div class="card-header bg-warning text-dark">
                    <strong>Combo bao gồm:</strong>
                </div>
                <div class="card-body">
                    @foreach($item->combo->comboPackageItems as $comboItem)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>
                                {{ $comboItem->quantity }}x {{ $comboItem->itemProductVariant->product->name ?? 'N/A' }}
                                @if($comboItem->itemProductVariant->productVariantOptions->isNotEmpty())
                                    <small class="text-muted">({{ $comboItem->itemProductVariant->productVariantOptions->pluck('attributeValue.value')->join(' - ') }})</small>
                                @endif
                            </span>
                            @if($comboItem->itemProductVariant->product->image_url)
                                <img src="{{ asset($comboItem->itemProductVariant->product->image_url) }}" alt="Ảnh sản phẩm" width="40" class="rounded">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- Đây là sản phẩm thường -->
        <h2>QR Sản phẩm</h2>
        <p><strong>Tên sản phẩm:</strong> {{ $item->productVariant?->product?->name }}</p>
        <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
    @endif
    
    @if($qrCodeBase64)
        <img src="{{ $qrCodeBase64 }}" alt="QR {{ $item->combo_id ? 'Combo' : 'Sản phẩm' }}" style="max-width:300px;">
        <br>
        <a href="{{ route('foods.print', ['item_id' => $item->id]) }}" class="btn btn-success mt-3" target="_blank">
            Tải PDF {{ $item->combo_id ? 'Combo' : 'Sản phẩm' }}
        </a>
    @else
        <p class="text-danger">Không tạo được mã QR!</p>
    @endif
</div>
@endsection