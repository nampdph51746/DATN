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
    public $ticketQrs = [];
    public $foodQr = null;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $qrcodeService = new QrcodeService();
        $this->qrcode = $qrcodeService->generateQrCode($booking->booking_code);

        // QR cho từng vé (ghế)
        if (method_exists($booking, 'tickets')) {
            foreach ($booking->tickets as $ticket) {
                $qrText = $ticket->ticket_code;
                $this->ticketQrs[$ticket->id] = $qrcodeService->generateQrCode($qrText);
            }
        }

        // QR cho combo đồ ăn/uống
        if (method_exists($booking, 'bookingItems') && $booking->bookingItems->count() > 0) {
            $foodText =  json_encode($booking->bookingItems->toArray());
            $this->foodQr = $qrcodeService->generateQrCode($foodText);
        }
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
                'ticketQrs' => $this->ticketQrs,
                'foodQr' => $this->foodQr,
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

        // Đính kèm QR code cho booking code
        $bookingQrFileName = 'booking_' . $this->booking->id . '_qrcode.png';
        $bookingQrFilePath = storage_path('app/temp/' . $bookingQrFileName);
        $bookingQrPath = $qrcodeService->generateQrCodeFile($this->booking->booking_code, $bookingQrFilePath);
        if ($bookingQrPath && file_exists($bookingQrPath)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($bookingQrPath)
                ->as($bookingQrFileName)
                ->withMime('image/png');
        }

        // Đính kèm QR code cho từng vé (ghế)
        if (method_exists($this->booking, 'tickets')) {
            foreach ($this->booking->tickets as $ticket) {
                $qrText = $ticket->ticket_code;
                $fileName = 'ticket_' . $ticket->id . '_qrcode.png';
                $filePath = storage_path('app/temp/' . $fileName);
                $qrcodeFilePath = $qrcodeService->generateQrCodeFile($qrText, $filePath);
                if ($qrcodeFilePath && file_exists($qrcodeFilePath)) {
                    $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($qrcodeFilePath)
                        ->as($fileName)
                        ->withMime('image/png');
                }
            }
        }

        // Đính kèm QR code cho đồ ăn/uống nếu có
        if (method_exists($this->booking, 'bookingItems') && $this->booking->bookingItems->count() > 0) {
            $foodText = json_encode($this->booking->bookingItems->toArray());
            $foodFileName = 'order_' . $this->booking->id . '_food_qrcode.png';
            $foodFilePath = storage_path('app/temp/' . $foodFileName);
            $foodQrPath = $qrcodeService->generateQrCodeFile($foodText, $foodFilePath);
            if ($foodQrPath && file_exists($foodQrPath)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($foodQrPath)
                    ->as($foodFileName)
                    ->withMime('image/png');
            }
        }

        return $attachments;
    }
}