<?php

namespace App\Enums;

enum ShowtimeStatus: string
{
    case Scheduled = 'scheduled';  
    case Ongoing = 'ongoing';      
    case Completed = 'completed';  
    case Cancelled = 'cancelled';  

    public function color(): string
    {
        return match($this) {
            self::Scheduled => 'bg-success',
            self::Ongoing => 'bg-primary',
            self::Completed => 'bg-secondary',
            self::Cancelled => 'bg-danger',
        };
    }
}
