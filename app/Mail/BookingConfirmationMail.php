<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\BarcodeService;
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
    public $barcode;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        
        // Tạo barcode cho booking
        $barcodeService = new BarcodeService();
        $this->barcode = $barcodeService->generateBarcode($booking->booking_code);
        
        // Debug log
        \Log::info('BookingConfirmationMail - Barcode generated', [
            'booking_code' => $booking->booking_code,
            'barcode_length' => strlen($this->barcode ?? ''),
            'barcode_starts_with' => substr($this->barcode ?? '', 0, 20)
        ]);
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
                'barcode' => $this->barcode,
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
        
        // Tạo barcode file attachment nếu cần
        if ($this->barcode) {
            $barcodeService = new BarcodeService();
            $fileName = $this->booking->booking_code . '_barcode.png';
            $filePath = storage_path('app/temp/' . $fileName);
            
            $barcodeFilePath = $barcodeService->generateBarcodeFile($this->booking->booking_code, $filePath);
            
            if ($barcodeFilePath && file_exists($barcodeFilePath)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($barcodeFilePath)
                    ->as($fileName)
                    ->withMime('image/png');
            }
        }
        
        return $attachments;
    }
}
