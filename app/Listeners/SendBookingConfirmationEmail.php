<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Mail\BookingConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void
    {
        try {
            // Load booking với các relationship cần thiết
            $booking = $event->booking->load([
                'user',
                'tickets.showtime.movie',
                'tickets.showtime.room.cinema',
                'tickets.seat'
            ]);
            
            // Load bookingItems riêng với productVariant.product
            $booking->load(['bookingItems' => function($query) {
                $query->with('productVariant.product');
            }]);

            // Gửi email
            Mail::to($booking->user->email)->send(new \App\Mail\BookingConfirmationMail($booking));

            Log::info('Booking confirmation email sent successfully', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'user_email' => $booking->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $event->booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Có thể throw lại exception để retry job nếu cần
            // throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(BookingConfirmed $event, \Throwable $exception): void
    {
        Log::error('Booking confirmation email job failed permanently', [
            'booking_id' => $event->booking->id,
            'error' => $exception->getMessage()
        ]);
    }
}
