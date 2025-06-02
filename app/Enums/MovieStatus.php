<?php

namespace App\Enums;

enum MovieStatus: string {
    case Showing = 'showing';
    case Upcoming = 'upcoming';
    case Ended = 'ended';
}