<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Booking;
use App\Enums\TicketStatus;
use App\Models\BookingItem;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketScanService
{
    /**
     * Quét vé và cập nhật trạng thái
     */
    public function scanTicket(string $bookingCode): array
    {
        try {
            DB::beginTransaction();

            // Tìm booking theo mã
            $booking = Booking::with(['tickets.showtime.movie', 'tickets.showtime.cinema', 'tickets.seat'])
                ->where('booking_code', $bookingCode)
                ->first();

            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng với mã: ' . $bookingCode
                ];
            }

            // Kiểm tra trạng thái booking
            if ($booking->status !== BookingStatus::Confirmed) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng chưa được xác nhận hoặc đã bị hủy'
                ];
            }

            // Kiểm tra thời gian chiếu (chỉ cho phép quét trước giờ chiếu tối đa 30 phút)
            // Tạm thời comment để test
            /*
            $firstTicket = $booking->tickets->first();
            if ($firstTicket && $firstTicket->showtime) {
                $showtimeStart = $firstTicket->showtime->start_time;
                $now = Carbon::now();
                
                if ($now->lt($showtimeStart->copy()->subMinutes(30))) {
                    return [
                        'success' => false,
                        'message' => 'Chưa đến thời gian có thể quét vé. Vui lòng quét trước giờ chiếu tối đa 30 phút.'
                    ];
                }
                
                // Kiểm tra đã quá giờ chiếu (sau 2 tiếng)
                if ($now->gt($showtimeStart->copy()->addHours(2))) {
                    return [
                        'success' => false,
                        'message' => 'Đã quá thời gian sử dụng vé.'
                    ];
                }
            }
            */

            // Lấy các vé thuộc booking này
            $tickets = $booking->tickets;

            if ($tickets->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy vé trong đơn hàng này'
                ];
            }

            // Kiểm tra xem có vé nào đã được sử dụng chưa
            $usedTickets = $tickets->where('status', TicketStatus::Used);
            if ($usedTickets->isNotEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Vé đã được sử dụng trước đó vào lúc: ' . $usedTickets->first()->used_at
                ];
            }

            // Cập nhật trạng thái tất cả vé thành "used"
            $updatedTickets = [];
            foreach ($tickets as $ticket) {
                $ticket->update([
                    'status' => TicketStatus::Used,
                    'used_at' => now(),
                    'scanned_by' => Auth::user()->id ?? null // Nếu có hệ thống auth cho nhân viên
                ]);

                // Reload ticket với relationships để hiển thị trong response
                $ticket->load('seat');
                $updatedTickets[] = $ticket;
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Quét vé thành công! Đã cập nhật ' . count($updatedTickets) . ' vé.',
                'booking' => [
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer_name ?? ($booking->user->name ?? 'N/A'),
                    'customer_phone' => $booking->customer_phone ?? ($booking->user->phone_number ?? 'N/A'),
                    'total_amount' => $booking->total_amount ?? 0,
                    // thêm các trường khác nếu cần
                ],
                'updated_tickets' => $updatedTickets
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi quét vé: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy trạng thái vé
     */
    public function getTicketStatus(string $bookingCode): array
    {
        try {
            $booking = Booking::with(['tickets.showtime.movie', 'tickets.showtime.cinema', 'tickets.seat'])
                ->where('booking_code', $bookingCode)
                ->first();

            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng với mã: ' . $bookingCode
                ];
            }

            // Lấy thông tin vé
            $tickets = $booking->tickets;

            $ticketInfo = $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'seat_name' => $ticket->seat ? ($ticket->seat->row_char . $ticket->seat->seat_number) : 'N/A',
                    'ticket_status' => $ticket->status,
                    'used_at' => $ticket->used_at,
                    'price' => $ticket->price_at_purchase ?? 0
                ];
            });

            $firstTicket = $tickets->first();
            $showtime = $firstTicket ? $firstTicket->showtime : null;

            return [
                'success' => true,
                'data' => [
                    'booking_code' => $booking->booking_code,
                    'booking_status' => $booking->status,
                    'total_amount' => $booking->total_amount,
                    'booking_date' => $booking->created_at,
                    'customer_name' => $booking->customer_name ?? ($booking->user->name ?? 'N/A'),
                    'customer_phone' => $booking->customer_phone ?? ($booking->user->phone_number ?? 'N/A'),
                    'showtime' => $showtime ? [
                        'movie_name' => $showtime->movie->name,
                        'cinema_name' => $showtime->cinema->name ?? 'N/A',
                        'show_date' => $showtime->show_date,
                        'start_time' => $showtime->formatted_start_time,
                    ] : null,
                    'tickets' => $ticketInfo
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin vé'
            ];
        }
    }
}
