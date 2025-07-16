@extends('layouts.client.client')



@section('content')

<div class="container mx-auto px-4 py-8 sm:py-12 max-w-3xl" style="padding-top: 120px;">
    <div class="text-center mb-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-green-600 mb-3 flex items-center justify-center gap-2">
            <span class="text-4xl">🎉</span> Thanh toán thành công!
        </h2>
        <p class="text-lg text-gray-600">Cảm ơn bạn đã đặt vé. Đơn hàng của bạn đã được xác nhận và lưu lại trong hệ thống.</p>
    </div>
    <div class="flex justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="h-4 bg-gradient-to-r from-gray-100 via-white to-gray-100" style="background: repeating-linear-gradient(90deg, #f3f4f6, #f3f4f6 8px, #fff 8px, #fff 16px);"></div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <p class="text-lg font-semibold text-pink-600">{{ $room->cinema->name }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ $movie->title ?? 'Tên phim' }}</p>
                </div>
                <div class="flex justify-center mb-4">
                    <img src="{{ $movie->image_path ? asset('storage/' . $movie->image_path) : asset('images/default-poster.jpg') }}" 
                         alt="{{ $movie->title ?? 'Poster' }}" 
                         class="w-32 rounded-lg shadow-md">
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
            <div class="h-4 bg-gradient-to-r from-gray-100 via-white to-gray-100" style="background: repeating-linear-gradient(90deg, #f3f4f6, #f3f4f6 8px, #fff 8px, #fff 16px);"></div>
            <div class="text-center py-4">
                <p class="text-sm text-gray-600">Mã vé: <span class="font-semibold">{{ $bookingCode ?? '---' }}</span></p>
            </div>
        </div>
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('client.home') }}" 
           class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
            Về trang chủ
        </a>
    </div>
</div>



@endsection

@if(session('success'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#e5006e'
        });
    });
</script>
@endif
