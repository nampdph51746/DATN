<?php

namespace App\Enums;

enum UserStatus: string {
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}

enum MovieStatus: string {
    case Showing = 'showing';
    case Upcoming = 'upcoming';
    case Ended = 'ended';
}

enum CinemaStatus: string {
    case Active = 'active';
    case Inactive = 'inactive';
}

enum SeatStatus: string {
    case Available = 'available';
    case Reserved = 'reserved';
    case Booked = 'booked';
}

enum ShowtimeStatus: string {
    case Active = 'active';
    case Cancelled = 'cancelled';
}

enum BookingStatus: string {
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}

enum TicketStatus: string {
    case Valid = 'valid';
    case Used = 'used';
    case Cancelled = 'cancelled';
}

enum ProductType: string {
    case Food = 'food';
    case Drink = 'drink';
    case Combo = 'combo';
}

enum PromotionDiscountType: string {
    case Percentage = 'percentage';
    case Fixed = 'fixed';
}

enum PointReasonType: string {
    case Earned = 'earned';
    case Spent = 'spent';
    case Expired = 'expired';
}

enum PaymentStatus: string {
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
}

enum NotificationType: string {
    case Booking = 'booking';
    case Promotion = 'promotion';
    case System = 'system';
}