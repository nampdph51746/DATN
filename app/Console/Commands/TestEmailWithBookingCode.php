<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class TestEmailWithBookingCode extends Command
{
    protected $signature = 'test:email-booking {code}';
    protected $description = 'Test email với booking code cụ thể';

    public function handle()
    {
        $bookingCode = $this->argument('code');
        
        $booking = Booking::with(['tickets.seat', 'tickets.showtime.movie', 'tickets.showtime.room.cinema'])
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            $this->error("Không tìm thấy booking: {$bookingCode}");
            return;
        }

        $this->info("=== THÔNG TIN BOOKING ===");
        $this->info("Code: {$booking->booking_code}");
        $this->info("Số vé: " . $booking->tickets->count());
        
        foreach ($booking->tickets as $i => $ticket) {
            $this->info("Vé " . ($i+1) . ": {$ticket->price_at_purchase} VNĐ - Ghế " . ($ticket->seat ? $ticket->seat->row_char . $ticket->seat->seat_number : 'N/A'));
        }

        $this->info("\n=== GỬI EMAIL ===");
        
        try {
            Mail::to('test@example.com')->send(new BookingConfirmationMail($booking));
            $this->info('✅ Email đã được gửi thành công!');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi gửi email: ' . $e->getMessage());
        }
    }
}
