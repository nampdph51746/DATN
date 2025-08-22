@extends('layouts.client.client')

@section('title', 'Voucher | CineVN')

@section('content')
<style>
.history-container {
    display: flex;
    justify-content: center;
    margin: 100px auto 60px auto;
    max-width: 1000px;
}
.history-box {
    flex: 1;
    background: white;
    border-radius: 0 12px 12px 0;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
[data-theme="dark"] .history-box {
    background-color: #1f2937;
    color: #f9fafb;
}
.voucher-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    background: #fafafa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
[data-theme="dark"] .voucher-card {
    background: #374151;
    border-color: #4b5563;
}
.voucher-info {
    flex: 1;
}
.voucher-name {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 5px;
}
.voucher-code {
    background: #6366f1;
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: bold;
}
.voucher-date {
    font-size: 13px;
    color: #666;
}
[data-theme="dark"] .voucher-date {
    color: #aaa;
}
</style>

<div class="history-container">
    @include('client.profile.menu')

    <div class="history-box">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:15px;">
            🎁 Danh sách Voucher
        </h3>

        @forelse($vouchers as $voucher)
            <div class="voucher-card">
                <div class="voucher-info">
                    <div class="voucher-name">{{ $voucher->name }}</div>
                    <div class="voucher-date">
                        ⏳ {{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y') }}
                        - {{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}
                    </div>
                    <div>Giảm: 
                        @if($voucher->discount_type == 'percentage')
                            {{ rtrim(rtrim(number_format($voucher->discount_value, 2), '0'), '.') }}%
                        @else
                            {{ number_format($voucher->discount_value, 0, ',', '.') }} VNĐ
                        @endif
                    </div>
                    @if($voucher->min_booking_value)
                        <div>Áp dụng cho đơn từ {{ number_format($voucher->min_booking_value, 0, ',', '.') }} VNĐ</div>
                    @endif
                </div>
                <div>
                    <span class="voucher-code">{{ $voucher->code }}</span>
                </div>
            </div>
        @empty
            <p>Không có voucher khả dụng.</p>
        @endforelse

        <div style="margin-top:20px;">
            {{ $vouchers->links() }}
        </div>
    </div>
</div>

@include('client.footer.footer')
@endsection