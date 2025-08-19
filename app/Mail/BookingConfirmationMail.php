<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\QrcodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $qrPaths = [];
    public $foodQrPath = null;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $qrcodeService = new QrcodeService();

        // booking QR
        $bookingQrFileName = 'booking_' . $booking->id . '_qrcode.png';
        $bookingQrFilePath = storage_path('app/temp/' . $bookingQrFileName);
        $qrcodeService->generateQrCodeFile($booking->booking_code, $bookingQrFilePath);
        $this->qrPaths['booking'] = $bookingQrFilePath;

        // ticket QRs
        if (method_exists($booking, 'tickets')) {
            foreach ($booking->tickets as $ticket) {
                $fileName = 'ticket_' . $ticket->id . '_qrcode.png';
                $filePath = storage_path('app/temp/' . $fileName);
                $qrcodeService->generateQrCodeFile($ticket->ticket_code, $filePath);
                $this->qrPaths['ticket_'.$ticket->id] = $filePath;
            }
        }

        // food QR
        if (method_exists($booking, 'bookingItems') && $booking->bookingItems->count() > 0) {
            $foodText = json_encode($booking->bookingItems->toArray());
            $foodFileName = 'order_' . $booking->id . '_food_qrcode.png';
            $foodFilePath = storage_path('app/temp/' . $foodFileName);
            $qrcodeService->generateQrCodeFile($foodText, $foodFilePath);
            $this->foodQrPath = $foodFilePath;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt vé - ' . $this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'booking'   => $this->booking,
                'qrPaths'   => $this->qrPaths,
                'foodQrPath'=> $this->foodQrPath,
            ]
        );
    }

    public function attachments(): array
    {
        // no attachments anymore → images will be embedded inline
        return [];
    }
}
