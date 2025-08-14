<?php

namespace App\Enums;

enum BookingStatus: string {
    case Pending = 'pending';
    case ConfirmedNotPrinted = 'confirmed_not_printed';
    case ConfirmedPrinted = 'confirmed_printed';
    case Cancelled = 'cancelled';
}