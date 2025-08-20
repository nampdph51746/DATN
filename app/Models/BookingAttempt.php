<?php

namespace App\Models;

use App\Enums\BookingAttemptStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'showtime_id',
        'seat_ids',
        'status',
        'reserved_at',
        'expired_at',
        'completed_at',
    ];

    protected $casts = [
        'seat_ids' => 'array',
        'status' => BookingAttemptStatus::class,
        'reserved_at' => 'datetime',
        'expired_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    /**
     * Kiểm tra attempt đã hết hạn chưa
     */
    public function isExpired(): bool
    {
        return now() > $this->expired_at;
    }

    /**
     * Đánh dấu attempt là timeout
     */
    public function markAsTimeout(): void
    {
        $this->update([
            'status' => BookingAttemptStatus::Timeout
        ]);
    }

    /**
     * Đánh dấu attempt là completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => BookingAttemptStatus::Completed,
            'completed_at' => now()
        ]);
    }

    /**
     * Đánh dấu attempt là cancelled
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'status' => BookingAttemptStatus::Cancelled
        ]);
    }

    /**
     * Scope để lấy các attempt thất bại (timeout hoặc cancelled)
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', [BookingAttemptStatus::Timeout, BookingAttemptStatus::Cancelled]);
    }

    /**
     * Scope để lấy các attempt trong khoảng thời gian
     */
    public function scopeInTimeRange($query, $from, $to)
    {
        return $query->whereBetween('reserved_at', [$from, $to]);
    }
}
