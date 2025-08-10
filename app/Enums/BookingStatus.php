<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Đang chờ',
            self::CONFIRMED => 'Đã xác nhận',
            self::CANCELLED => 'Đã hủy',
            self::REFUNDED => 'Đã hoàn tiền',
            self::EXPIRED => 'Hết hạn',
        };
    }
}