<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'room_id', 'start_time', 'end_time', 'base_price', 'status'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'base_price' => 'decimal:2',
        'status' => \App\Enums\ShowtimeStatus::class,
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function showtimeSeatStates()
    {
        return $this->hasMany(ShowtimeSeatState::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Kiểm tra và cập nhật trạng thái tự động
     */
    public function autoUpdateStatus(): bool
    {
        $service = new \App\Services\ShowtimeStatusService();
        return $service->updateSingleShowtimeStatus($this);
    }

    /**
     * Scope để lấy các suất chiếu cần cập nhật trạng thái
     */
    public function scopeNeedsStatusUpdate($query)
    {
        $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
        
        return $query->where(function ($q) use ($now) {
            // scheduled -> ongoing
            $q->where('status', \App\Enums\ShowtimeStatus::Scheduled)
              ->where('start_time', '<=', $now)
              ->where('end_time', '>', $now);
        })->orWhere(function ($q) use ($now) {
            // ongoing -> completed
            $q->where('status', \App\Enums\ShowtimeStatus::Ongoing)
              ->where('end_time', '<=', $now);
        })->orWhere(function ($q) use ($now) {
            // scheduled -> completed (missed ongoing)
            $q->where('status', \App\Enums\ShowtimeStatus::Scheduled)
              ->where('end_time', '<=', $now);
        });
    }
}