@extends('layouts.client.client')

@section('title', 'Thanh toán thất bại')

@section('content')

<div class="container mx-auto px-4 py-8 sm:py-12 max-w-3xl" style="padding-top: 120px;">
    <div class="text-center mb-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-red-600 mb-3 flex items-center justify-center gap-2">
            <span class="text-4xl">❌</span> Thanh toán thất bại!
        </h2>
        <p class="text-lg text-gray-600">Rất tiếc, đã xảy ra lỗi trong quá trình thanh toán. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.</p>
    </div>
    
    @if (session('error'))
        <div class="mb-8">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-red-400 text-xl">⚠️</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Chi tiết lỗi:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($movie) && isset($showtime) && isset($room))
    <div class="flex justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden border-l-4 border-red-500">
            <div class="h-4 bg-gradient-to-r from-red-100 via-white to-red-100" style="background: repeating-linear-gradient(90deg, #fef2f2, #fef2f2 8px, #fff 8px, #fff 16px);"></div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <p class="text-lg font-semibold text-pink-600">{{ $room->cinema->name ?? 'Rạp chiếu phim' }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ $movie->title ?? 'Tên phim' }}</p>
                </div>
                <div class="flex justify-center mb-4">
                    <img src="{{ $movie->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg') }}" 
                         alt="{{ $movie->title ?? 'Poster' }}" 
                         class="w-32 rounded-lg shadow-md opacity-75">
                </div>  
                <div class="grid grid-cols-3 gap-4 text-center mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">PHÒNG</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $room->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">HÀNG</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $ticketRow ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">GHẾ</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $ticketSeat ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">GIÁ</p>
                        <p class="text-lg font-semibold text-gray-800">{{ number_format($totalPrice ?? 0) }} ₫</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">NGÀY</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $showtime?->start_time?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">GIỜ</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $showtime?->start_time?->format('H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="h-4 bg-gradient-to-r from-red-100 via-white to-red-100" style="background: repeating-linear-gradient(90deg, #fef2f2, #fef2f2 8px, #fff 8px, #fff 16px);"></div>
            <div class="text-center py-4 bg-red-50">
                <p class="text-sm text-red-600 font-medium">❌ Giao dịch không thành công</p>
                @if(isset($bookingCode))
                <p class="text-sm text-gray-600">Mã đặt vé: <span class="font-semibold">{{ $bookingCode }}</span></p>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <div class="text-center mt-8 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @php
                $hasBookingId = session('failed_booking_id') || request()->get('booking_id');
                $hasBookingInfo = session('booking_info');
                $canRetry = $hasBookingId || $hasBookingInfo || isset($bookingCode);
            @endphp
            
            @if($canRetry)
            <a href="{{ route('client.failed.retry') }}{{ session('failed_booking_id') ? '?booking_id=' . session('failed_booking_id') : '' }}" 
               class="inline-block bg-red-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-red-700 transition-colors">
                Thử lại thanh toán
            </a>
            @else
            <a href="javascript:history.back()" 
               class="inline-block bg-red-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-red-700 transition-colors">
                Quay lại
            </a>
            @endif
            <a href="{{ route('client.home') }}" 
               class="inline-block bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors">
                Về trang chủ
            </a>
        </div>
        <p class="text-sm text-gray-500 mt-4">
            Nếu vấn đề vẫn tiếp tục, vui lòng liên hệ với bộ phận hỗ trợ khách hàng.
        </p>
        
        {{-- Debug info (remove in production) --}}
        @if(config('app.debug'))
        <div class="mt-4 text-xs text-gray-400">
            <p>Debug: failed_booking_id = {{ session('failed_booking_id') ?? 'null' }}</p>
            <p>Debug: booking_info = {{ session('booking_info') ? 'exists' : 'null' }}</p>
            <p>Debug: bookingCode = {{ isset($bookingCode) ? $bookingCode : 'null' }}</p>
            <p>Debug: can_retry = {{ $canRetry ? 'true' : 'false' }}</p>
        </div>
        @endif
    </div>
</div>

@endsection

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Thanh toán thất bại!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc2626'
        });
    });
</script>
@endif