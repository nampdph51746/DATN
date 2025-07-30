<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AutoConfirmPendingBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = Carbon::now();
        $bookings = Booking::where('status', BookingStatus::Pending)
            ->where('created_at', '<=', $now->subMinutes(10))
            ->get();

        foreach ($bookings as $booking) {
            $booking->status = BookingStatus::Confirmed;
            $booking->save();
        }
    }
}
