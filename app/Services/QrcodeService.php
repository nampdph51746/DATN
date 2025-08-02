<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

class QrcodeService
{
    /**
     * Tạo QR code từ text và trả về base64
     */
    public function generateQrCode($text, $size = 300)
    {
        try {
            $qr = new QrCode($text);
            $writer = new PngWriter();
            $result = $writer->write($qr);
            return base64_encode($result->getString());
        } catch (\Exception $e) {
            Log::error('QR code generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo QR code và lưu vào file
     */
    public function generateQrCodeFile($text, $filePath, $size = 300)
    {
        try {
            $qr = new QrCode($text);
            $writer = new PngWriter();
            $result = $writer->write($qr);
            $directory = dirname($filePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($filePath, $result->getString());
            return $filePath;
        } catch (\Exception $e) {
            Log::error('QR code file generation failed: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Tạo QR code cho booking và các vé liên quan
     */
    public static function generateQrsForBooking($booking)
    {
        $qrService = app(self::class);

        // 1. QR cho từng vé (ghế)
        if (method_exists($booking, 'tickets')) {
            $tickets = $booking->tickets;
            foreach ($tickets as $ticket) {
                $qrText =  $ticket->ticket_code;
                $filePath = storage_path('app/public/qrcodes/tickets/ticket_' . $ticket->id . '.png');
                $qrService->generateQrCodeFile($qrText, $filePath);
            }
        }

        // 2. QR cho tất cả đồ ăn/uống của đơn hàng
        if (method_exists($booking, 'bookingItems') && $booking->bookingItems->count() > 0) {
            $foodText = json_encode($booking->bookingItems->toArray());
            $foodQrPath = storage_path('app/public/qrcodes/foods/order_' . $booking->id . '.png');
            $qrService->generateQrCodeFile($foodText, $foodQrPath);
        }
    }

    /**
     * Tạo mã booking unique để làm QR code
     */
    public function generateBookingCode($bookingId, $prefix = 'TICKET')
    {
        return $prefix . str_pad($bookingId, 8, '0', STR_PAD_LEFT) . strtoupper(substr(md5($bookingId . time()), 0, 4));
    }
}


