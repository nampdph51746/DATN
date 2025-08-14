<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestBookingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:booking-email {booking_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test gửi email xác nhận booking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookingId = $this->argument('booking_id');
        
        if (!$bookingId) {
            // Lấy booking mới nhất
            $booking = Booking::with([
                'user',
                'tickets.showtime.movie',
                'tickets.showtime.room.cinema',
                'tickets.seat',
                'bookingItems.productVariant.product'
            ])->latest()->first();
            
            if (!$booking) {
                $this->error('Không tìm thấy booking nào trong database!');
                return 1;
            }
            
            $this->info("Sử dụng booking mới nhất: #{$booking->id}");
        } else {
            $booking = Booking::with([
                'user',
                'tickets.showtime.movie',
                'tickets.showtime.room.cinema',
                'tickets.seat',
                'bookingItems.productVariant.product'
            ])->find($bookingId);
            
            if (!$booking) {
                $this->error("Không tìm thấy booking với ID: {$bookingId}");
                return 1;
            }
        }
        
        try {
            $this->info("Đang gửi email đến: {$booking->user->email}");
            $this->info("Booking code: {$booking->booking_code}");
            
            Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));
            
            $this->info('✅ Email đã được gửi thành công!');
            
        } catch (\Exception $e) {
            $this->error('❌ Lỗi khi gửi email: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
