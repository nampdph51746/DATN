<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $item->combo_id ? 'Combo' : 'Sản phẩm' }} - {{ $item->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .qr-section {
            text-align: center;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .combo-items {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $item->combo_id ? 'QR COMBO' : 'QR SẢN PHẨM' }}</h2>
        <p>Cinema Booking System</p>
    </div>

    <div class="content">
        @if($item->combo_id)
            <!-- Thông tin combo -->
            <h3>🍿 {{ $item->combo->name }}</h3>
            <p><strong>Số lượng combo:</strong> {{ $item->quantity }}</p>
            <p><strong>Giá:</strong> {{ number_format($item->price_at_purchase, 0, ',', '.') }} đ</p>
            
            <div class="combo-items">
                <strong>Combo bao gồm:</strong>
                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->combo->comboPackageItems as $comboItem)
                            <tr>
                                <td>{{ $comboItem->itemProductVariant->product->name ?? 'N/A' }}</td>
                                <td>
                                    @if($comboItem->itemProductVariant->productVariantOptions->isNotEmpty())
                                        {{ $comboItem->itemProductVariant->productVariantOptions->pluck('attributeValue.value')->join(' - ') }}
                                    @else
                                        Mặc định
                                    @endif
                                </td>
                                <td>{{ $comboItem->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Thông tin sản phẩm thường -->
            <h3>{{ $item->productVariant?->product?->name }}</h3>
            <p><strong>SKU:</strong> {{ $item->productVariant?->sku }}</p>
            <p><strong>Số lượng:</strong> {{ $item->quantity }}</p>
            <p><strong>Giá:</strong> {{ number_format($item->price_at_purchase, 0, ',', '.') }} đ</p>
            @if($item->productVariant && $item->productVariant->productVariantOptions->isNotEmpty())
                <p><strong>Biến thể:</strong> {{ $item->productVariant->productVariantOptions->pluck('attributeValue.value')->join(' - ') }}</p>
            @endif
        @endif

        <table>
            <tr>
                <th>ID Item</th>
                <td>{{ $item->id }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>{{ ucfirst($item->product_status) }}</td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="qr-section">
        @if($qrCodeBase64)
            <p><strong>Mã QR {{ $item->combo_id ? 'Combo' : 'Sản phẩm' }}:</strong></p>
            <img src="{{ $qrCodeBase64 }}" alt="QR Code" style="max-width: 200px;">
        @else
            <p>Không thể tạo mã QR</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>Vui lòng xuất trình mã QR này khi nhận {{ $item->combo_id ? 'combo' : 'sản phẩm' }}</p>
        <p>In lúc: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>