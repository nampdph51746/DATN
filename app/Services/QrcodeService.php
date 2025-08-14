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
     * Tạo QR code chi tiết cho vé (ghế)
     */
    public function generateTicketQrCode($ticket)
    {
        $ticketData = [
            'type' => 'ticket',
            'ticket_id' => $ticket->id,
            'ticket_code' => $ticket->ticket_code,
            'booking_code' => $ticket->booking->booking_code,
            'showtime_id' => $ticket->showtime_id,
            'seat_info' => [
                'seat_id' => $ticket->seat_id,
                'seat_name' => $ticket->seat->name ?? null,
                'seat_type' => $ticket->seat->seatType->name ?? null,
                'room' => $ticket->showtime->room->name ?? null,
                'cinema' => $ticket->showtime->cinema->name ?? null,
            ],
            'movie_info' => [
                'title' => $ticket->showtime->movie->title ?? null,
                'showtime' => $ticket->showtime->start_time ?? null,
            ],
            'customer_info' => [
                'name' => $ticket->booking->customer_name,
                'phone' => $ticket->booking->customer_phone,
                'email' => $ticket->booking->customer_email,
            ],
            'price' => $ticket->price_at_purchase,
            'created_at' => $ticket->created_at
        ];

        return json_encode($ticketData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Tạo QR code chi tiết cho đồ ăn/uống
     */
    public function generateFoodQrCode($bookingItems)
    {
        $foodData = [
            'type' => 'food',
            'booking_id' => $bookingItems->first()->booking_id ?? null,
            'booking_code' => $bookingItems->first()->booking->booking_code ?? null,
            'items' => [],
            'total_items' => $bookingItems->count(),
            'total_amount' => $bookingItems->sum(function($item) {
                return $item->quantity * $item->price_at_purchase;
            }),
            'customer_info' => [
                'name' => $bookingItems->first()->booking->customer_name ?? null,
                'phone' => $bookingItems->first()->booking->customer_phone ?? null,
            ],
            'created_at' => $bookingItems->first()->created_at ?? null
        ];

        foreach ($bookingItems as $item) {
            $foodData['items'][] = [
                'item_id' => $item->id,
                'product_name' => $item->productVariant->product->name ?? 'N/A',
                'variant_sku' => $item->productVariant->sku ?? null,
                'quantity' => $item->quantity,
                'unit_price' => $item->price_at_purchase,
                'total_price' => $item->quantity * $item->price_at_purchase,
                'status' => $item->ticket_status->value ?? 'unused'
            ];
        }

        return json_encode($foodData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Tạo QR code tổng hợp cho đơn hàng
     */
    public function generateBookingQrCode($booking)
    {
        $bookingData = [
            'type' => 'booking',
            'booking_id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'status' => $booking->status->value,
            'customer_info' => [
                'name' => $booking->customer_name,
                'phone' => $booking->customer_phone,
                'email' => $booking->customer_email,
            ],
            'payment_info' => [
                'total_before_discount' => $booking->total_amount_before_discount,
                'discount_amount' => $booking->discount_amount,
                'final_amount' => $booking->final_amount,
                'payment_method' => $booking->paymentMethod->name ?? null,
            ],
            'tickets_info' => [
                'count' => $booking->tickets->count(),
                'seats' => $booking->tickets->map(function($ticket) {
                    return [
                        'ticket_code' => $ticket->ticket_code,
                        'seat_name' => $ticket->seat->name ?? null,
                        'seat_type' => $ticket->seat->seatType->name ?? null,
                    ];
                })->toArray()
            ],
            'food_items' => [
                'count' => $booking->bookingItems->count(),
                'total_amount' => $booking->bookingItems->sum(function($item) {
                    return $item->quantity * $item->price_at_purchase;
                })
            ],
            'showtime_info' => [
                'movie_title' => $booking->showtime->movie->title ?? null,
                'cinema_name' => $booking->showtime->cinema->name ?? null,
                'room_name' => $booking->showtime->room->name ?? null,
                'start_time' => $booking->showtime->start_time ?? null,
            ],
            'created_at' => $booking->created_at
        ];

        return json_encode($bookingData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Tạo QR code cho booking và các vé liên quan (phiên bản cải tiến)
     */
    public static function generateQrsForBooking($booking)
    {
        $qrService = app(self::class);

        try {
            // Load các relationship cần thiết
            $booking->load(['tickets.seat.seatType', 'tickets.showtime.movie', 'tickets.showtime.cinema', 'tickets.showtime.room', 'bookingItems.productVariant.product', 'paymentMethod']);

            // 1. QR cho từng vé (ghế) - chi tiết hơn
            if ($booking->tickets && $booking->tickets->count() > 0) {
                foreach ($booking->tickets as $ticket) {
                    $qrText = $qrService->generateTicketQrCode($ticket);
                    $filePath = storage_path('app/public/qrcodes/tickets/ticket_' . $ticket->id . '.png');
                    $qrService->generateQrCodeFile($qrText, $filePath);
                    Log::info('Generated QR for ticket: ' . $ticket->ticket_code);
                }
            }

            // 2. QR cho tất cả đồ ăn/uống của đơn hàng - chi tiết hơn
            if ($booking->bookingItems && $booking->bookingItems->count() > 0) {
                $qrText = $qrService->generateFoodQrCode($booking->bookingItems);
                $foodQrPath = storage_path('app/public/qrcodes/foods/booking_' . $booking->id . '_food.png');
                $qrService->generateQrCodeFile($qrText, $foodQrPath);
                Log::info('Generated QR for food items in booking: ' . $booking->booking_code);
            }

            // 3. QR tổng hợp cho toàn bộ đơn hàng
            $bookingQrText = $qrService->generateBookingQrCode($booking);
            $bookingQrPath = storage_path('app/public/qrcodes/bookings/booking_' . $booking->id . '.png');
            $qrService->generateQrCodeFile($bookingQrText, $bookingQrPath);
            Log::info('Generated QR for booking: ' . $booking->booking_code);

        } catch (\Exception $e) {
            Log::error('Error generating QR codes for booking: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mã booking unique để làm QR code
     */
    public function generateBookingCode($bookingId, $prefix = 'TICKET')
    {
        return $prefix . str_pad($bookingId, 8, '0', STR_PAD_LEFT) . strtoupper(substr(md5($bookingId . time()), 0, 4));
    }

    /**
     * Lấy đường dẫn QR code cho vé
     */
    public function getTicketQrPath($ticketId)
    {
        return storage_path('app/public/qrcodes/tickets/ticket_' . $ticketId . '.png');
    }

    /**
     * Lấy đường dẫn QR code cho đồ ăn của booking
     */
    public function getFoodQrPath($bookingId)
    {
        return storage_path('app/public/qrcodes/foods/booking_' . $bookingId . '_food.png');
    }

    /**
     * Lấy đường dẫn QR code cho booking
     */
    public function getBookingQrPath($bookingId)
    {
        return storage_path('app/public/qrcodes/bookings/booking_' . $bookingId . '.png');
    }
}

