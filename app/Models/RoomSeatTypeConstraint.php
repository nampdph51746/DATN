<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSeatTypeConstraint extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'seat_type_id', 
        'min_percentage',
        'max_percentage',
        'is_required'
    ];

    protected $casts = [
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
        'is_required' => 'boolean',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }
}
