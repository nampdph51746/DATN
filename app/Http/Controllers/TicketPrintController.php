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
}
?>