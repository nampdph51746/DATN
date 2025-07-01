<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RoomSeatConfiguration extends Model
{
    protected $table = 'room_seat_configurations';
    protected $fillable = ['room_id', 'seat_type_id', 'percentage'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }
}