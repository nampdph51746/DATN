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

    // Lấy các loại ghế được phép cho phòng này dựa trên constraint
    public function allowedSeatTypes()
    {
        // Trả về collection các model SeatType được phép cho phòng này, chỉ lấy những cái hợp lệ
        $constraints = \App\Models\RoomSeatTypeConstraint::where('room_type_id', $this->room_type_id)
            ->with('seatType')
            ->get();
        return $constraints->map(function($constraint) {
            return $constraint->seatType instanceof \App\Models\SeatType ? $constraint->seatType : null;
        })->filter(function($seatType) {
            return $seatType !== null;
        })->values();
    }

    // Kiểm tra xem phòng có suất chiếu đang hoạt động không
    public function hasActiveShowtimes()
    {
        return $this->showtimes()
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereDate('start_time', '>=', now()->toDateString())
            ->exists();
    }

    // Kiểm tra xem phòng có thể chỉnh sửa không
    public function canBeEdited()
    {
        // Không thể chỉnh sửa nếu phòng có suất chiếu đang hoạt động
        return !$this->hasActiveShowtimes();
    }
}