<?php

namespace App\Enums;

enum NotificationType: string {
    case Booking = 'booking';
    case Promotion = 'promotion';
    case System = 'system';
}