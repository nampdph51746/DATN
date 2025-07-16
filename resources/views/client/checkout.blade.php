@extends('layouts.client.client')

@section('content')
@php
// Uncomment this line to debug the selected seats
    // $bookingData = session('booking_preview');
    // dd($bookingData); // Uncomment this line to debug the booking data
@endphp

<div class="container text-center py-5">
    @if ($bookingData)
        <h2>Đang xử lý thanh toán...</h2>
        <p>Vui lòng chờ giây lát để chuyển sang VNPay</p>

        <form id="autoSubmitForm" action="{{ route('checkout.vnpay') }}" method="POST">
            @csrf
            <input type="hidden" name="final_amount" value="{{ $bookingData['final_amount'] }}">
            <input type="hidden" name="payment_method_id" value="{{ $bookingData['payment_method_id'] }}">
            <input type="hidden" name="booking_code" value="{{ $bookingData['booking_code'] }}">
            <input type="hidden" name="from_check" value="1">

            {{-- Thêm các dữ liệu khác nếu cần --}}
            <input type="hidden" name="user_id" value="{{ $bookingData['user_id'] }}">
            <input type="hidden" name="movie_title" value="{{ $bookingData['movie_title'] }}">
            <input type="hidden" name="cinema_name" value="{{ $bookingData['cinema_name'] }}">
            <input type="hidden" name="room_name" value="{{ $bookingData['room_name'] }}">
            <input type="hidden" name="showtime" value="{{ $bookingData['showtime'] }}">
            <input type="hidden" name="snack_items" value="{{ $bookingData['snack_items'] }}">
            <input type="hidden" name="total_amount_before_discount" value="{{ $bookingData['total_amount_before_discount'] }}">
            <input type="hidden" name="discount_amount" value="{{ $bookingData['discount_amount'] }}">
            <input type="hidden" name="promotion_id" value="{{ $bookingData['promotion_id'] }}">
             <input type="hidden" name="redirect" value="1">
            <button type="submit" style="display: none">Gửi</button>
        </form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            const form = document.getElementById('autoSubmitForm');
            if (form) {
                form.submit();
            } else {
                console.error('Form not found!');
            }
        }, 1000); // 1 giây
    });
</script>

    @else
        <h2 style="color: red;">Không tìm thấy dữ liệu đặt vé</h2>
        <p>Vui lòng quay lại và thực hiện đặt vé lại.</p>
    @endif
</div>
@endsection
