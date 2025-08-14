<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\QrScanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class QrScanController extends Controller
{
    protected $qrScanService;

    public function __construct(QrScanService $qrScanService)
    {
        $this->qrScanService = $qrScanService;
    }

    /**
     * Hiển thị trang quét QR
     */
    public function showScanPage()
    {
        return view('admin.qr-scan.index');
    }

    /**
     * Xử lý quét QR code
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $result = $this->qrScanService->scanQrCode($request->qr_data);
            
            if ($result['success']) {
                Log::info('QR scan successful', [
                    'qr_data' => $request->qr_data,
                    'type' => $result['type'] ?? 'unknown',
                    'user_id' => Auth::id()
                ]);
            } else {
                Log::warning('QR scan failed', [
                    'qr_data' => $request->qr_data,
                    'reason' => $result['message'],
                    'user_id' => Auth::id()
                ]);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('QR scan exception', [
                'error' => $e->getMessage(),
                'qr_data' => $request->qr_data,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi quét QR code'
            ], 500);
        }
    }

    /**
     * Quét vé cụ thể
     */
    public function scanTicket(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|integer|exists:tickets,id'
        ]);

        $result = $this->qrScanService->scanTicket($request->ticket_id);
        return response()->json($result);
    }

    /**
     * Quét đồ ăn của booking
     */
    public function scanFood(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id'
        ]);

        $result = $this->qrScanService->scanFoodItems($request->booking_id);
        return response()->json($result);
    }

    /**
     * Quét toàn bộ booking
     */
    public function scanBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id'
        ]);

        $result = $this->qrScanService->scanBooking($request->booking_id);
        return response()->json($result);
    }

    /**
     * Lấy lịch sử quét QR
     */
    public function scanHistory(Request $request)
    {
        // Có thể implement sau để xem lịch sử quét
        return view('admin.qr-scan.history');
    }
}
