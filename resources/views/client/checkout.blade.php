@extends('layouts.client.client')

<style>
    .lazy-placeholder {
        background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .lazy-loaded {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">

    <div class="container flex items-center justify-center min-h-screen px-4 py-8">
        <div
            class="flex flex-col items-center justify-center w-full max-w-md p-8 space-y-6 bg-gradient-to-br from-white to-indigo-50 rounded-3xl shadow-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            @if ($bookingData)
                <div
                    class="relative flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-md animate-pulse">
                    <div class="absolute w-16 h-16 border-8 border-indigo-100 border-t-indigo-600 rounded-full animate-spin">
                    </div>
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" class="relative z-10">
                        <path
                            d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 3a1 1 0 0 1 1 1v5.586l3.293 3.293a1 1 0 0 1-1.414 1.414l-3.586-3.586A1 1 0 0 1 11 12V6a1 1 0 0 1 1-1z"
                            fill="#4f46e5" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-center text-indigo-900 sm:text-3xl">Đang xử lý thanh toán...</h2>
                <p class="text-center text-gray-600 sm:text-lg max-w-xs">Vui lòng chờ giây lát để chuyển sang VNPay</p>
                <img src="https://cdn.vnpay.vn/assets/images/logo_vnpay.png" alt="VNPay"
                    class="w-16 mt-4 transition-transform duration-300 hover:scale-110 lazy-loaded" loading="lazy"
                    onload="this.classList.add('lazy-loaded'); this.classList.remove('lazy-placeholder')"
                    class="lazy-placeholder w-16 h-12 mt-4 rounded" />
                <form id="autoSubmitForm" action="{{ route('checkout.vnpay') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="final_amount" value="{{ $bookingData['final_amount'] }}">
                    <input type="hidden" name="payment_method_id" value="{{ $bookingData['payment_method_id'] }}">
                    <input type="hidden" name="booking_code" value="{{ $bookingData['booking_code'] }}">
                    <input type="hidden" name="from_check" value="1">
                    <input type="hidden" name="user_id" value="{{ $bookingData['user_id'] }}">
                    <input type="hidden" name="movie_title" value="{{ $bookingData['movie_title'] }}">
                    <input type="hidden" name="cinema_name" value="{{ $bookingData['cinema_name'] }}">
                    <input type="hidden" name="room_name" value="{{ $bookingData['room_name'] }}">
                    <input type="hidden" name="showtime" value="{{ $bookingData['showtime'] }}">
                    <input type="hidden" name="snack_items" value="{{ $bookingData['snack_items'] }}">
                    <input type="hidden" name="total_amount_before_discount"
                        value="{{ $bookingData['total_amount_before_discount'] }}">
                    <input type="hidden" name="discount_amount" value="{{ $bookingData['discount_amount'] }}">
                    <input type="hidden" name="promotion_id" value="{{ $bookingData['promotion_id'] }}">
                    <input type="hidden" name="redirect" value="1">
                    <button type="submit">Gửi</button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            const form = document.getElementById('autoSubmitForm');
                            if (form) {
                                form.submit();
                            } else {
                                console.error('Form not found!');
                            }
                        }, 1000);
                    });
                </script>
            @else
                <div class="flex items-center justify-center w-24 h-24 bg-red-50 rounded-full shadow-md">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24">
                        <path
                            d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm-1 7a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V9zm1 8a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5z"
                            fill="#ef4444" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-center text-red-600 sm:text-3xl">Không tìm thấy dữ liệu đặt vé</h2>
                <p class="text-center text-gray-600 sm:text-lg max-w-xs">Vui lòng quay lại và thực hiện đặt vé lại.</p>
            @endif
        </div>
    </div>
@endsection
