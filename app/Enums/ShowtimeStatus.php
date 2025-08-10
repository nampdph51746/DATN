<?php

namespace App\Enums;

enum ShowtimeStatus: string
{
    case Scheduled = 'scheduled';  
    case Ongoing = 'ongoing';      
    case Completed = 'completed';  
    case Cancelled = 'cancelled';  
    case Postponed = 'postponed';  // Suất chiếu bị hoãn

    public function color(): string
    {
        return match($this) {
            self::Scheduled => 'bg-success',
            self::Ongoing => 'bg-primary',
            self::Completed => 'bg-secondary',
            self::Cancelled => 'bg-danger',
            self::Postponed => 'bg-warning',
        };
    }
}
