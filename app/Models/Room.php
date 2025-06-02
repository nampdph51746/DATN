<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['cinema_id', 'room_type_id', 'name', 'capacity', 'status'];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}