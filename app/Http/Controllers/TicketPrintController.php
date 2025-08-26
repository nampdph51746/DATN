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
        $item = BookingItem::with([
            'combo.comboPackageItems.itemProductVariant.product',
            'combo.comboPackageItems.itemProductVariant.productVariantOptions.attributeValue',
            'productVariant.product'
        ])->findOrFail($item_id);
        
        $qrService = app(QrcodeService::class);
        // Chỉ sử dụng ID của booking item để tạo QR, không phải JSON
        $qrRaw = $qrService->generateQrCode((string)$item->id, 240);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        return view('admin.foods.qr', [
            'item' => $item,
            'qrCodeBase64' => $qrCodeBase64
        ]);
    }
    // API in vé: chỉ trả về mã QR, không thay đổi trạng thái
    public function printTicketApi($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        
        // Kiểm tra trạng thái vé
        if ($ticket->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Vé đã bị huỷ!']);
        }
        
        if ($ticket->status === 'valid') {
            return response()->json(['success' => false, 'message' => 'Vé chưa được kiểm tra!']);
        }
        
        $qrService = app(QrcodeService::class);
        $qrRaw = $qrService->generateQrCode($ticket->ticket_code, 180);
        return response()->json([
            'success' => true,
            'qr' => $qrRaw,
            'message' => 'Tạo QR vé thành công!'
        ]);
    }

    // API in đồ ăn: chỉ trả về mã QR, không thay đổi trạng thái
    public function printFoodApi($itemId)
    {
        $item = BookingItem::findOrFail($itemId);
        
        // Kiểm tra trạng thái
        if ($item->product_status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Đồ ăn đã bị huỷ!']);
        }
        
        if ($item->product_status === 'valid') {
            return response()->json(['success' => false, 'message' => 'Đồ ăn chưa được kiểm tra!']);
        }
        
        $qrService = app(QrcodeService::class);
        // Chỉ sử dụng ID của booking item để tạo QR
        $qrRaw = $qrService->generateQrCode((string)$item->id, 180);
        return response()->json([
            'success' => true,
            'qr' => $qrRaw,
            'message' => 'Tạo QR đồ ăn thành công!'
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
        $item = BookingItem::with([
            'combo.comboPackageItems.itemProductVariant.product',
            'combo.comboPackageItems.itemProductVariant.productVariantOptions.attributeValue',
            'productVariant.product'
        ])->findOrFail($item_id);
        
        $qrService = app(QrcodeService::class);
        // Chỉ sử dụng ID của booking item để tạo QR
        $qrRaw = $qrService->generateQrCode((string)$item->id, 180);
        $qrCodeBase64 = $qrRaw ? 'data:image/png;base64,' . $qrRaw : null;
        $pdf = Pdf::loadView('admin.foods.print', [
            'item' => $item,
            'qrCodeBase64' => $qrCodeBase64
        ]);
        
        $fileName = $item->combo_id 
            ? 'combo-' . $item->combo->name . '-' . $item->id . '.pdf'
            : 'san-pham-' . $item->id . '.pdf';
            
        return $pdf->download($fileName);
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