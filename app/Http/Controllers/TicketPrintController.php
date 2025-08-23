<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Booking;
use App\Services\QrcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\TicketPrintService;
use App\Models\BookingItem;

class TicketPrintController extends Controller
{
    // Hiển thị QR cho từng vé
    public function showTicketQr($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode($ticket->ticket_code, 240);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        return view('admin.tickets.qr', [
            'ticket' => $ticket,
            'qrCodeBase64' => $qrCodeBase64
        ]);
    }

    // Hiển thị QR cho từng sản phẩm
    public function showFoodQr($item_id)
    {
        $item = BookingItem::findOrFail($item_id);
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode(json_encode([
            'id' => $item->id,
            'name' => $item->productVariant?->product?->name,
            'quantity' => $item->quantity
        ]), 240);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        return view('admin.foods.qr', [
            'item' => $item,
            'qrCodeBase64' => $qrCodeBase64
        ]);
    }
    // API in vé: chuyển trạng thái sang used và trả về mã QR
    public function printTicketApi($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        if (in_array($ticket->status, ['used', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Vé đã được in hoặc huỷ!']);
        }
        // Chỉ cho phép in vé có trạng thái checked
        if ($ticket->status !== 'checked') {
            return response()->json(['success' => false, 'message' => 'Vé chưa được kiểm tra!']);
        }
        $ticket->status = 'used';
        $ticket->used_at = now();
        $ticket->save();
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode($ticket->ticket_code, 180);
        return response()->json([
            'success' => true,
            'qr' => $qrRaw,
            'message' => 'In vé thành công!'
        ]);
    }

    // API in đồ ăn: chuyển trạng thái sang used và trả về mã QR
    public function printFoodApi($itemId)
    {
        $item = BookingItem::findOrFail($itemId);
        if (in_array($item->product_status, ['used', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Đồ ăn đã được in hoặc huỷ!']);
        }
        // Chỉ cho phép in đồ ăn có trạng thái checked
        if ($item->product_status !== 'checked') {
            return response()->json(['success' => false, 'message' => 'Đồ ăn chưa được kiểm tra!']);
        }
        $item->product_status = 'used';
        $item->used_at = now();
        $item->save();
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode(json_encode([
            'id' => $item->id,
            'name' => $item->productVariant?->product?->name,
            'quantity' => $item->quantity
        ]), 180);
        return response()->json([
            'success' => true,
            'qr' => $qrRaw,
            'message' => 'In đồ ăn thành công!'
        ]);
    }
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

        // Cập nhật trạng thái của tất cả các đồ ăn sang checked
        foreach ($bookingItems as $item) {
            // Kiểm tra cả giá trị enum và string
            $currentStatus = is_object($item->product_status) ? $item->product_status->value : $item->product_status;
            if ($currentStatus === 'valid') {
                $item->product_status = 'checked';
                $item->checked_at = now();
                $item->save();
            }
        }
        
        // Refresh để lấy dữ liệu mới nhất
        $bookingItems = $booking->bookingItems()->get();

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

    // Tải PDF cho vé
    public function printTicketPdf($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        if ($ticket->status !== 'used') {
            $ticket->status = 'used';
            $ticket->used_at = now();
            $ticket->save();
        }
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode($ticket->ticket_code, 180);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        $pdf = Pdf::loadView('admin.tickets.print', [
            'ticket' => $ticket,
            'qrCodeBase64' => $qrCodeBase64
        ]);
        return $pdf->download('ve-' . $ticket->ticket_code . '.pdf');
    }

    // Tải PDF cho sản phẩm
    public function printFoodPdf($item_id)
    {
        $item = BookingItem::findOrFail($item_id);
        if ($item->product_status !== 'used') {
            $item->product_status = 'used';
            $item->used_at = now();
            $item->save();
        }
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode(json_encode([
            'id' => $item->id,
            'name' => $item->productVariant?->product?->name,
            'quantity' => $item->quantity
        ]), 180);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        $pdf = Pdf::loadView('admin.foods.print', [
            'item' => $item,
            'qrCodeBase64' => $qrCodeBase64
        ]);
        return $pdf->download('san-pham-' . $item->id . '.pdf');
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
