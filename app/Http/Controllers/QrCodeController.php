<?php

namespace App\Http\Controllers;

use App\Services\TicketScanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QrCodeController extends Controller
{
    protected $ticketScanService;

    public function __construct(TicketScanService $ticketScanService)
    {
        $this->ticketScanService = $ticketScanService;
    }

    /**
     * Quét mã QR và cập nhật trạng thái vé
     */
    public function scanQr(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_code' => 'required|string|max:50'
            ]);

            $bookingCode = $request->input('booking_code');
            
            $result = $this->ticketScanService->scanTicket($bookingCode);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'booking' => $result['booking'],
                        'updated_tickets' => $result['updated_tickets']
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi quét mã'
            ], 500);
        }
    }

    /**
     * Kiểm tra trạng thái vé bằng booking code
     */
    public function checkTicketStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_code' => 'required|string|max:50'
            ]);

            $bookingCode = $request->input('booking_code');
            
            $result = $this->ticketScanService->getTicketStatus($bookingCode);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra trạng thái vé'
            ], 500);
        }
    }

    /**
     * Quét mã QR theo ticket_code (từng vé)
     */
    public function scanTicketByCode(Request $request)
    {
        try {
            $request->validate([
                'ticket_code' => 'required|string|max:50'
            ]);

            $ticketCode = $request->input('ticket_code');
            if (!method_exists($this->ticketScanService, 'scanTicketByCode')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chức năng chưa được hỗ trợ trên server.'
                ], 400);
            }
            $result = $this->ticketScanService->scanTicketByCode($ticketCode);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data'] ?? null
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi quét vé'
            ], 500);
        }
    }
}
