<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\BookingItem;
use App\Enums\TicketStatus;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QrScanService
{
    /**
     * Quét và xử lý QR code
     */
    public function scanQrCode(string $qrData): array
    {
        try {
            // Thử decode JSON để xem có phải QR chi tiết không
            $decodedData = json_decode($qrData, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedData) && isset($decodedData['type'])) {
                // QR code chi tiết
                return $this->handleDetailedQrCode($decodedData);
            } else {
                // QR code đơn giản (chỉ là ticket_code hoặc booking_code)
                return $this->handleSimpleQrCode($qrData);
            }
        } catch (\Exception $e) {
            Log::error('QR scan error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi quét QR code: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý QR code chi tiết (JSON format)
     */
    private function handleDetailedQrCode(array $qrData): array
    {
        switch ($qrData['type']) {
            case 'ticket':
                return $this->handleTicketQr($qrData);
            case 'food':
                return $this->handleFoodQr($qrData);
            case 'booking':
                return $this->handleBookingQr($qrData);
            default:
                return [
                    'success' => false,
                    'message' => 'Loại QR code không được hỗ trợ'
                ];
        }
    }

    /**
     * Xử lý QR code đơn giản (text)
     */
    private function handleSimpleQrCode(string $qrData): array
    {
        // Thử tìm ticket trước
        $ticket = Ticket::where('ticket_code', $qrData)->first();
        if ($ticket) {
            return $this->scanTicket($ticket->id);
        }

        // Thử tìm booking
        $booking = Booking::where('booking_code', $qrData)->first();
        if ($booking) {
            return $this->scanBooking($booking->id);
        }

        return [
            'success' => false,
            'message' => 'Không tìm thấy vé hoặc đơn hàng với mã: ' . $qrData
        ];
    }

    /**
     * Xử lý QR code vé chi tiết
     */
    private function handleTicketQr(array $qrData): array
    {
        if (!isset($qrData['ticket_id'])) {
            return [
                'success' => false,
                'message' => 'QR code vé không hợp lệ'
            ];
        }

        return $this->scanTicket($qrData['ticket_id']);
    }

    /**
     * Xử lý QR code đồ ăn chi tiết
     */
    private function handleFoodQr(array $qrData): array
    {
        if (!isset($qrData['booking_id'])) {
            return [
                'success' => false,
                'message' => 'QR code đồ ăn không hợp lệ'
            ];
        }

        return $this->scanFoodItems($qrData['booking_id']);
    }

    /**
     * Xử lý QR code booking chi tiết
     */
    private function handleBookingQr(array $qrData): array
    {
        if (!isset($qrData['booking_id'])) {
            return [
                'success' => false,
                'message' => 'QR code đơn hàng không hợp lệ'
            ];
        }

        return $this->scanBooking($qrData['booking_id']);
    }

    /**
     * Quét vé cụ thể
     */
    public function scanTicket(int $ticketId): array
    {
        try {
            DB::beginTransaction();

            $ticket = Ticket::with(['booking', 'showtime.movie', 'seat'])->find($ticketId);
            
            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy vé'
                ];
            }

            // Kiểm tra trạng thái booking
            if (!in_array($ticket->booking->status, [BookingStatus::ConfirmedNotPrinted, BookingStatus::ConfirmedPrinted])) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng chưa được xác nhận hoặc đã bị hủy'
                ];
            }

            // Kiểm tra vé đã được sử dụng chưa
            if ($ticket->status === TicketStatus::Used) {
                return [
                    'success' => false,
                    'message' => 'Vé đã được sử dụng lúc: ' . $ticket->used_at->format('d/m/Y H:i:s'),
                    'ticket' => $ticket
                ];
            }

            // Cập nhật trạng thái vé
            $ticket->update([
                'status' => TicketStatus::Used,
                'used_at' => Carbon::now(),
                'scanned_by' => Auth::id()
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Quét vé thành công',
                'ticket' => $ticket,
                'type' => 'ticket'
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Ticket scan error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi quét vé: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Quét đồ ăn của booking
     */
    public function scanFoodItems(int $bookingId): array
    {
        try {
            DB::beginTransaction();

            $booking = Booking::with(['bookingItems.productVariant.product'])->find($bookingId);
            
            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ];
            }

            // Kiểm tra trạng thái booking
            if (!in_array($booking->status, [BookingStatus::ConfirmedNotPrinted, BookingStatus::ConfirmedPrinted])) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng chưa được xác nhận hoặc đã bị hủy'
                ];
            }

            $foodItems = $booking->bookingItems;
            if ($foodItems->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng không có đồ ăn/uống'
                ];
            }

            // Kiểm tra xem có item nào chưa được sử dụng không
            $unusedItems = $foodItems->where('ticket_status', '!=', TicketStatus::Used);
            if ($unusedItems->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tất cả đồ ăn/uống đã được sử dụng',
                    'food_items' => $foodItems
                ];
            }

            // Cập nhật trạng thái các item chưa sử dụng
            foreach ($unusedItems as $item) {
                $item->update([
                    'ticket_status' => TicketStatus::Used,
                    'used_at' => Carbon::now(),
                    'scanned_by' => Auth::id()
                ]);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Quét đồ ăn/uống thành công',
                'food_items' => $foodItems,
                'updated_items' => $unusedItems,
                'type' => 'food'
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Food items scan error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi quét đồ ăn/uống: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Quét toàn bộ booking
     */
    public function scanBooking(int $bookingId): array
    {
        try {
            DB::beginTransaction();

            $booking = Booking::with(['tickets.seat', 'bookingItems.productVariant.product', 'tickets.showtime.movie'])
                ->find($bookingId);
            
            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ];
            }

            // Kiểm tra trạng thái booking
            if (!in_array($booking->status, [BookingStatus::ConfirmedNotPrinted, BookingStatus::ConfirmedPrinted])) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng chưa được xác nhận hoặc đã bị hủy'
                ];
            }

            $result = [
                'success' => true,
                'message' => 'Quét đơn hàng thành công',
                'booking' => $booking,
                'type' => 'booking',
                'tickets_scanned' => 0,
                'food_items_scanned' => 0
            ];

            // Quét tất cả vé chưa sử dụng
            $unusedTickets = $booking->tickets->where('status', '!=', TicketStatus::Used);
            foreach ($unusedTickets as $ticket) {
                $ticket->update([
                    'status' => TicketStatus::Used,
                    'used_at' => Carbon::now(),
                    'scanned_by' => Auth::id()
                ]);
                $result['tickets_scanned']++;
            }

            // Quét tất cả đồ ăn chưa sử dụng
            $unusedFoodItems = $booking->bookingItems->where('ticket_status', '!=', TicketStatus::Used);
            foreach ($unusedFoodItems as $item) {
                $item->update([
                    'ticket_status' => TicketStatus::Used,
                    'used_at' => Carbon::now(),
                    'scanned_by' => Auth::id()
                ]);
                $result['food_items_scanned']++;
            }

            DB::commit();
            return $result;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking scan error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi khi quét đơn hàng: ' . $e->getMessage()
            ];
        }
    }
}
