<?php

namespace App\Enums;

enum SeatStatus: string {
    case Available = 'available';
    case Reserved = 'reserved';
    case Booked = 'booked';
}
