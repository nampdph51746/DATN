<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

use App\Models\Booking;
use App\Services\QrcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\TicketPrintService;

class TicketPrintController extends Controller
{
    // In vé riêng theo ticket_code
    public function printTicket($ticket_code)
    {
        $ticket = Ticket::with(['showtime', 'seat', 'booking'])
            ->where('ticket_code', $ticket_code)
            ->firstOrFail();

        // Validate thời gian in vé
        $this->validatePrintTime($ticket);

        // Sinh QR code base64
        $qrCodeBase64 = null;
        if ($ticket) {
            $qrText = $ticket->ticket_code;
            $qrService = app(QrcodeService::class);
            $qrCodeRaw = $qrService->generateQrCode($qrText, 120);
            if ($qrCodeRaw) {
                $qrCodeBase64 = 'data:image/png;base64,' . $qrCodeRaw;
            }
        }

        $pdf = Pdf::loadView('admin.tickets.print', [
            'ticket' => $ticket,
            'booking' => $ticket->booking,
            'qrCodeBase64' => $qrCodeBase64
        ]);

        return $pdf->download('ve-' . $ticket_code . '.pdf');
    }

    // In chung đồ ăn, đồ uống theo booking_code
    public function printBookingItems($booking_code)
    {
        $booking = Booking::with(['bookingItems.productVariant'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        $bookingItems = $booking->bookingItems;

        // Sinh QR code cho booking items
        $qrCodeBase64 = null;
        if ($booking && $bookingItems->count() > 0) {
            $qrText = json_encode($bookingItems->toArray());
            $qrService = app(QrcodeService::class);
            $qrCodeRaw = $qrService->generateQrCode($qrText, 120);
            if ($qrCodeRaw) {
                $qrCodeBase64 = 'data:image/png;base64,' . $qrCodeRaw;
            }
        }

        $pdf = Pdf::loadView('booking_items.print', [
            'booking' => $booking,
            'bookingItems' => $bookingItems,
            'qrCodeBase64' => $qrCodeBase64
        ]);

        return $pdf->download('do-an-do-uong-' . $booking_code . '.pdf');
    }

    /**
     * Validate thời gian in vé
     * Vé chỉ được in trước suất chiếu 1 tiếng và không được in sau khi suất chiếu đã bắt đầu
     */
    private function validatePrintTime($ticket)
    {
        $showtime = $ticket->showtime;
        $currentTime = now();
        $showtimeStart = $showtime->start_time;
        
        // Kiểm tra nếu suất chiếu đã bắt đầu
        if ($currentTime >= $showtimeStart) {
            abort(403, 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' . $showtimeStart->format('d/m/Y H:i'));
        }
        
        // Kiểm tra nếu còn ít hơn 1 tiếng trước suất chiếu
        $oneHourBeforeShowtime = $showtimeStart->copy()->subHour();
        if ($currentTime > $oneHourBeforeShowtime) {
            abort(403, 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng. Suất chiếu: ' . $showtimeStart->format('d/m/Y H:i'));
        }
    }
}
?>