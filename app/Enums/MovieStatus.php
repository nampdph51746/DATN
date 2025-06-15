<?php

namespace App\Enums;

enum MovieStatus: string {
    case Showing = 'showing';
    case Active = 'active';
    case Inactive = 'inactive';
    case Upcoming = 'upcoming';
    case Ended = 'ended';
}