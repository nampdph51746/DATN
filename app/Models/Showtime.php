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

    /**
     * Get show date from start_time
     */
    public function getShowDateAttribute()
    {
        return $this->start_time ? $this->start_time->format('Y-m-d') : null;
    }

    /**
     * Get formatted start time
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('H:i') : null;
    }

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
     * Kiểm tra xem suất chiếu có vé đã được đặt hay không
     */
    public function hasBookedTickets()
    {
        return $this->tickets()
            ->whereNotNull('booking_id')
            ->exists();
    }

    /**
     * Kiểm tra xem suất chiếu có thể chỉnh sửa hay không
     */
    public function canBeEdited()
    {
        // Không thể chỉnh sửa nếu đã có vé được đặt
        if ($this->hasBookedTickets()) {
            return false;
        }

        // Không thể chỉnh sửa nếu trạng thái là completed hoặc cancelled
        $statusValue = is_object($this->status) ? $this->status->value : $this->status;
        if (in_array($statusValue, ['completed', 'cancelled'])) {
            return false;
        }

        // Không thể chỉnh sửa nếu suất chiếu đã bắt đầu (ongoing)
        if ($statusValue === 'ongoing') {
            return false;
        }

        return true;
    }

    /**
     * Get cinema through room
     */
    public function cinema()
    {
        return $this->hasOneThrough(
            Cinema::class,
            Room::class,
            'id', // Foreign key on rooms table
            'id', // Foreign key on cinemas table
            'room_id', // Local key on showtimes table
            'cinema_id' // Local key on rooms table
        );
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