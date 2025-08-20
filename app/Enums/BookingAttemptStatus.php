<?php

namespace App\Enums;

enum BookingAttemptStatus: string 
{
    case Reserved = 'reserved';      // Đang giữ ghế
    case Timeout = 'timeout';        // Hết thời gian giữ ghế
    case Cancelled = 'cancelled';    // Người dùng hủy
    case Completed = 'completed';    // Hoàn thành thanh toán
}
