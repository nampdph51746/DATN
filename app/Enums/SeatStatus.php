<?php

namespace App\Enums;

enum SeatStatus: string {
    case Available = 'available';
    case Maintenance = 'maintenance';
    case Reserved = 'reserved';
    case Booked = 'booked';
}
