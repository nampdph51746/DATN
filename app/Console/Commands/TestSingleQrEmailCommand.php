<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class TestSingleQrEmailCommand extends Command
{
    protected $signature = 'email:test-single-qr {booking_id?}';
    protected $description = 'Test gửi email với QR code đơn cho booking';

    public function handle()
    {
        $bookingId = $this->argument('booking_id');
        
        if ($bookingId) {
            $booking = Booking::with([
                'user',
                'tickets.seat.seatType', 
                'tickets.showtime.movie', 
                'tickets.showtime.cinema', 
                'tickets.showtime.room', 
                'bookingItems.productVariant.product', 
                'paymentMethod'
            ])->find($bookingId);
            
            if (!$booking) {
                $this->error("Không tìm thấy booking với ID: {$bookingId}");
                return;
            }
        } else {
            $booking = Booking::with([
                'user',
                'tickets.seat.seatType', 
                'tickets.showtime.movie', 
                'tickets.showtime.cinema', 
                'tickets.showtime.room', 
                'bookingItems.productVariant.product', 
                'paymentMethod'
            ])->first();
            
            if (!$booking) {
                $this->error("Không có booking nào trong database");
                return;
            }
        }

        $this->info("Testing email với QR đơn cho booking: {$booking->booking_code}");
        
        try {
            // Tạo email
            $mail = new BookingConfirmationMail($booking);
            
            $this->info("✓ Email object được tạo thành công");
            
            // Test QR code generation
            if (!empty($mail->qrcode)) {
                $this->info("✓ QR code được tạo thành công (length: " . strlen($mail->qrcode) . " chars)");
            } else {
                $this->error("✗ QR code không được tạo");
                return;
            }
            
            // Preview email content
            $content = $mail->content();
            $this->info("✓ Email content được tạo với view: " . $content->view);
            
            $viewData = $content->with;
            $this->info("✓ View data contains:");
            $this->line("  - booking: " . ($viewData['booking'] ? "✓" : "✗"));
            $this->line("  - qrcode: " . ($viewData['qrcode'] ? "✓ (QR length: " . strlen($viewData['qrcode']) . ")" : "✗"));
            
            // Kiểm tra attachments
            $attachments = $mail->attachments();
            $this->info("✓ Email có " . count($attachments) . " attachment(s)");
            
            foreach ($attachments as $index => $attachment) {
                $this->line("  Attachment " . ($index + 1) . ": " . ($attachment ? "✓" : "✗"));
            }
            
            // Test gửi email (nếu có email config)
            if ($this->confirm('Bạn có muốn thực sự gửi email không?', false)) {
                if (!$booking->user || !$booking->user->email) {
                    $this->error("User hoặc email không tồn tại cho booking này");
                    return;
                }
                
                Mail::to($booking->user->email)->send($mail);
                $this->info("✓ Email đã được gửi thành công đến: " . $booking->user->email);
            } else {
                $this->info("Email không được gửi (chỉ test local)");
            }
            
            $this->info("\n=== Thông tin booking ===");
            $this->line("Booking code: {$booking->booking_code}");
            $this->line("Customer: {$booking->customer_name}");
            $this->line("Email: " . ($booking->user->email ?? 'N/A'));
            $this->line("Tickets: {$booking->tickets->count()}");
            $this->line("Food items: {$booking->bookingItems->count()}");
            $this->line("Total amount: " . number_format((float)$booking->final_amount, 0, ',', '.') . " VNĐ");
            
        } catch (\Exception $e) {
            $this->error("Lỗi khi test email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
