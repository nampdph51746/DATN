<?php

namespace App\Mail;


use App\Models\Booking;
use App\Services\QrcodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $qrcode;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        
        // Load các relationship cần thiết
        $booking->load(['tickets.seat.seatType', 'tickets.showtime.movie', 'tickets.showtime.cinema', 'tickets.showtime.room', 'bookingItems.productVariant.product', 'paymentMethod']);
        
        $qrcodeService = new QrcodeService();
        
        // Chỉ tạo QR tổng hợp cho booking - không tạo QR riêng cho vé và đồ ăn
        $bookingQrText = $qrcodeService->generateBookingQrCode($booking);
        $this->qrcode = $qrcodeService->generateQrCode($bookingQrText);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt vé - ' . $this->booking->booking_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'booking' => $this->booking,
                'qrcode' => $this->qrcode,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        $qrcodeService = new QrcodeService();

        // Load các relationship cần thiết
        $this->booking->load(['tickets.seat.seatType', 'tickets.showtime.movie', 'tickets.showtime.cinema', 'tickets.showtime.room', 'bookingItems.productVariant.product', 'paymentMethod']);

        // Chỉ đính kèm QR code tổng hợp cho booking
        $bookingQrText = $qrcodeService->generateBookingQrCode($this->booking);
        $bookingQrFileName = 'booking_' . $this->booking->booking_code . '_qrcode.png';
        $bookingQrFilePath = storage_path('app/temp/' . $bookingQrFileName);
        $bookingQrPath = $qrcodeService->generateQrCodeFile($bookingQrText, $bookingQrFilePath);
        
        if ($bookingQrPath && file_exists($bookingQrPath)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($bookingQrPath)
                ->as($bookingQrFileName)
                ->withMime('image/png');
        }

        return $attachments;
    }
}