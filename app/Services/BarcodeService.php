<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeService
{
    protected $generator;

    public function __construct()
    {
        $this->generator = new BarcodeGeneratorPNG();
    }

    /**
     * Tạo barcode từ text và trả về base64
     */
    public function generateBarcode($text, $type = BarcodeGeneratorPNG::TYPE_CODE_128, $widthFactor = 2, $height = 30)
    {
        try {
            // Tạo barcode binary data
            $barcodeData = $this->generator->getBarcode($text, $type, $widthFactor, $height);
            
            // Convert sang base64
            return base64_encode($barcodeData);
        } catch (\Exception $e) {
            \Log::error('Barcode generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo barcode và lưu vào file
     */
    public function generateBarcodeFile($text, $filePath, $type = BarcodeGeneratorPNG::TYPE_CODE_128, $widthFactor = 2, $height = 30)
    {
        try {
            $barcodeData = $this->generator->getBarcode($text, $type, $widthFactor, $height);
            
            // Đảm bảo thư mục tồn tại
            $directory = dirname($filePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Lưu file
            file_put_contents($filePath, $barcodeData);
            
            return $filePath;
        } catch (\Exception $e) {
            \Log::error('Barcode file generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo mã booking unique để làm barcode
     */
    public function generateBookingCode($bookingId, $prefix = 'TICKET')
    {
        return $prefix . str_pad($bookingId, 8, '0', STR_PAD_LEFT) . strtoupper(substr(md5($bookingId . time()), 0, 4));
    }
}
