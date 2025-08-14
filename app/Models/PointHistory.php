<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointHistory extends Model
{
    use HasFactory;

    protected $table = 'point_histories';
    protected $fillable = ['user_id', 'booking_id', 'points_change', 'reason_type', 'description'];

    protected $casts = [
        'reason_type' => \App\Enums\PointReasonType::class,
    ];

    protected static function booted()
    {
        static::saved(function ($history) {
            $booking = $history->booking;

            if (!$booking) {
                return;
            }

            // Nếu là cộng điểm thì đặt lại trạng thái là Đã xác nhận (chưa in vé)
            if ($history->points_change > 0) {
                $booking->status = BookingStatus::ConfirmedNotPrinted;
            }

            // Nếu là trừ điểm thì chuyển về trạng thái Chờ xác nhận
            if ($history->points_change < 0) {
                $booking->status = \App\Enums\BookingStatus::Pending;
            }

            $booking->save();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}