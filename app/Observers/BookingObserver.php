<?php

namespace App\Observers;

use App\Models\Booking;
use App\Enums\BookingStatus;

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
            $booking->user->updateRankByTotalSpent();
        }
    }
}
