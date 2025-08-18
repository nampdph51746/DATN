<?php

namespace App\Observers;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking)
    {
        // Chỉ kiểm tra khi booking chuyển sang confirmed
        if ($booking->status === BookingStatus::Confirmed) {
            if ($booking->user) {
                Log::info("BookingObserver: Booking {$booking->id} confirmed, checking rank for user {$booking->user_id}");
                $booking->user->updateRankByTotalSpent();
            }
        }
    }

    /**
     * Handle the Booking "created" event (nếu tạo booking với status confirmed)
     */
    public function created(Booking $booking)
    {
        if ($booking->status === BookingStatus::Confirmed && $booking->user) {
            Log::info("BookingObserver: Booking {$booking->id} created with confirmed status, checking rank for user {$booking->user_id}");
            $booking->user->updateRankByTotalSpent();
        }
    }
}
