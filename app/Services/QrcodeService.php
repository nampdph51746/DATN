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
     * Tạo mã booking unique để làm QR code
     */
    public function generateBookingCode($bookingId, $prefix = 'TICKET')
    {
        return $prefix . str_pad($bookingId, 8, '0', STR_PAD_LEFT) . strtoupper(substr(md5($bookingId . time()), 0, 4));
    }
}
