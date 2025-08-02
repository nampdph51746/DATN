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
        
        // Tạo QR code cho booking
        $qrcodeService = new QrcodeService();
        $this->qrcode = $qrcodeService->generateQrCode($booking->booking_code);
        
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
        
        // Tạo QR code file attachment nếu cần
        if ($this->qrcode) {
            $qrcodeService = new QrcodeService();
            $fileName = $this->booking->booking_code . '_qrcode.png';
            $filePath = storage_path('app/temp/' . $fileName);
            $qrcodeFilePath = $qrcodeService->generateQrCodeFile($this->booking->booking_code, $filePath);
            if ($qrcodeFilePath && file_exists($qrcodeFilePath)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($qrcodeFilePath)
                    ->as($fileName)
                    ->withMime('image/png');
            }
        }
        
        return $attachments;
    }
}
