<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ShowtimeSeatState;
use App\Models\Combo;
use App\Enums\BookingStatus;
use App\Enums\SeatStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentFailedController extends Controller
{
    public function show(Request $request)
    {
        // Lấy thông tin booking từ session hoặc database
        $bookingId = $request->get('booking_id') ?? Session::get('failed_booking_id');
        $errorMessage = $request->get('error') ?? Session::get('payment_error');
        
        $booking = null;
        $movie = null;
        $room = null;
        $showtime = null;
        $ticketRow = null;
        $ticketSeat = null;
        $totalPrice = 0;
        $bookingCode = null;

        try {
            if ($bookingId) {
                $booking = Booking::with([
                    'tickets.seat', 
                    'tickets.showtime.movie', 
                    'tickets.showtime.room.cinema',
                    'showtimeSeatStates.seat',
                    'showtimeSeatStates.showtime.movie',
                    'showtimeSeatStates.showtime.room.cinema'
                ])
                ->where('id', $bookingId)
                ->first();
            } 
            
            // Fallback: Lấy booking mới nhất của user nếu có đăng nhập
            if (!$booking && Auth::check()) {
                $booking = Booking::with([
                    'tickets.seat', 
                    'tickets.showtime.movie', 
                    'tickets.showtime.room.cinema',
                    'showtimeSeatStates.seat',
                    'showtimeSeatStates.showtime.movie',
                    'showtimeSeatStates.showtime.room.cinema'
                ])
                ->where('user_id', Auth::id())
                ->latest('created_at')
                ->first();
            }

            if ($booking) {
                $totalPrice = $booking->final_amount;
                $bookingCode = $booking->booking_code;
                
                // Ưu tiên lấy từ tickets trước
                if ($booking->tickets && $booking->tickets->isNotEmpty()) {
                    $ticket = $booking->tickets->first();
                    $movie = $ticket->showtime->movie ?? null;
                    $room = $ticket->showtime->room ?? null;
                    $showtime = $ticket->showtime ?? null;
                    $seat = $ticket->seat ?? null;
                    $ticketRow = $seat->row_char ?? null;
                    $ticketSeat = $seat->seat_number ?? null;
                } 
                // Fallback: Lấy từ showtime_seat_states
                else if ($booking->showtimeSeatStates && $booking->showtimeSeatStates->isNotEmpty()) {
                    $seatState = $booking->showtimeSeatStates->first();
                    $showtime = $seatState->showtime ?? null;
                    $movie = $showtime ? $showtime->movie : null;
                    $room = $showtime ? $showtime->room : null;
                    $seat = $seatState->seat ?? null;
                    $ticketRow = $seat ? $seat->row_char : null;
                    $ticketSeat = $seat ? $seat->seat_number : null;  
                }
            }

            // Lấy thông tin từ session cho các trường hợp đặc biệt
            if ((!isset($movie) || !$movie) && Session::has('booking_info')) {
                $bookingInfo = Session::get('booking_info');
                $totalPrice = $totalPrice ?? $bookingInfo['total_price'] ?? 0;
                $ticketRow = $ticketRow ?? $bookingInfo['seat_row'] ?? null;
                $ticketSeat = $ticketSeat ?? $bookingInfo['seat_number'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Error loading payment failed page: ' . $e->getMessage());
            // Không cần throw exception, chỉ log lại và hiển thị trang với thông tin tối thiểu
        }

        // Flash error message to session if provided
        if ($errorMessage) {
            Session::flash('error', $errorMessage);
        }

        // Chỉ xóa payment_error, giữ lại failed_booking_id và booking_info cho retry
        Session::forget(['payment_error']);

        // Debug log để kiểm tra dữ liệu
        if (config('app.debug')) {
            Log::info('PaymentFailedController debug data:', [
                'booking_id' => $bookingId,
                'movie' => $movie ? $movie->title : null,
                'room' => $room ? $room->name : null,
                'showtime' => $showtime ? $showtime->start_time : null,
                'ticketRow' => $ticketRow,
                'ticketSeat' => $ticketSeat,
                'totalPrice' => $totalPrice,
                'bookingCode' => $bookingCode,
                'booking_exists' => $booking ? true : false
            ]);
        }

        return view('client.payment.failed', compact(
            'movie', 
            'room', 
            'showtime', 
            'ticketRow', 
            'ticketSeat', 
            'totalPrice', 
            'bookingCode'
        ));
    }

    /**
     * Store failed payment information for display
     */
    public function storeFailedInfo(Request $request)
    {
        $bookingId = $request->get('booking_id');
        $errorMessage = $request->get('error', 'Đã xảy ra lỗi trong quá trình thanh toán.');
        
        if ($bookingId) {
            Session::put('failed_booking_id', $bookingId);
        }
        
        // Store error message in session
        Session::put('payment_error', $errorMessage);
        
        Log::info('Payment failed for booking: ' . $bookingId . ' - Error: ' . $errorMessage);
        
        return redirect()->route('client.failed')->with('error', $errorMessage);
    }

    /**
     * Handle payment failure from payment gateways
     */
    public function handlePaymentFailure(Request $request)
    {
        $bookingId = $request->get('booking_id');
        $errorCode = $request->get('error_code', 'PAYMENT_FAILED');
        $errorMessage = $this->getErrorMessage($errorCode);
        
        if ($bookingId) {
            // Có thể cập nhật trạng thái booking nếu cần
            try {
                $booking = Booking::find($bookingId);
                if ($booking) {
                    // Log payment failure
                    Log::warning("Payment failed for booking {$bookingId}: {$errorMessage}");
                }
            } catch (\Exception $e) {
                Log::error('Error handling payment failure: ' . $e->getMessage());
            }
        }
        
        return $this->storeFailedInfo($request->merge(['error' => $errorMessage]));
    }

    /**
     * Retry payment - restore booking data and redirect to payment
     */
    public function retryPayment(Request $request)
    {
        try {
            // Lấy booking ID từ request hoặc session
            $bookingId = $request->get('booking_id') ?? Session::get('failed_booking_id');
            
            Log::info('Retry payment attempt', [
                'booking_id_from_request' => $request->get('booking_id'),
                'booking_id_from_session' => Session::get('failed_booking_id'),
                'final_booking_id' => $bookingId,
                'user_id' => Auth::id()
            ]);
            
            if (!$bookingId) {
                Log::warning('No booking ID found for retry payment');
                // Nếu không có booking ID, thử lấy thông tin từ session booking_info
                $bookingInfo = Session::get('booking_info');
                if (!$bookingInfo) {
                    Log::error('No booking info found in session');
                    return redirect()->route('client.home')->with('error', 'Không tìm thấy thông tin đặt vé để thử lại.');
                }
                
                // Redirect về trang chọn ghế để đặt lại
                Log::info('Redirecting to home due to missing booking info');
                return redirect()->route('client.home')->with('info', 'Vui lòng chọn lại vé để tiếp tục thanh toán.');
            }

            // Lấy thông tin booking
            $booking = Booking::with([
                'tickets.seat', 
                'tickets.showtime.movie', 
                'tickets.showtime.room.cinema',
                'bookingItems.productVariant.product'
            ])->find($bookingId);

            if (!$booking) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return redirect()->route('client.home')->with('error', 'Không tìm thấy thông tin đặt vé.');
            }

            Log::info('Found booking for retry', [
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'user_id' => $booking->user_id
            ]);

            // Kiểm tra xem booking đã được thanh toán chưa
            if ($booking->status === BookingStatus::Confirmed->value) {
                Log::info('Booking already confirmed, redirecting to success');
                return redirect()->route('client.success')->with('info', 'Đơn hàng này đã được thanh toán thành công.');
            }

            // Kiểm tra quyền sở hữu booking
            if (Auth::id() && $booking->user_id != Auth::id()) {
                Log::warning('User trying to retry payment for booking not owned by them', [
                    'auth_user_id' => Auth::id(),
                    'booking_user_id' => $booking->user_id
                ]);
                return redirect()->route('client.home')->with('error', 'Bạn không có quyền truy cập đơn hàng này.');
            }

            // Tạo lại session data từ showtime_seat_states thay vì tickets
            $selectedSeatsInfo = [];
            $showtime = null;

            // Lấy thông tin từ showtime_seat_states nếu booking có ticket
            if ($booking->tickets->isNotEmpty()) {
                $tickets = $booking->tickets;
                foreach ($tickets as $ticket) {
                    $seat = $ticket->seat;
                    if ($ticket->showtime) {
                        $showtime = $ticket->showtime; // Lấy showtime từ ticket
                    }
                    
                    $selectedSeatsInfo[] = [
                        'seat_id' => $seat->id,
                        'row_char' => $seat->row_char,
                        'seat_number' => $seat->seat_number,
                        'price' => $ticket->price_at_purchase,
                    ];
                }
            } else {
                // Nếu chưa có tickets, lấy từ showtime_seat_states
                $seatStates = \App\Models\ShowtimeSeatState::with(['seat', 'showtime'])
                    ->where('booking_id', $booking->id)
                    ->get();
                
                if ($seatStates->isNotEmpty()) {
                    $showtime = $seatStates->first()->showtime;
                    
                    foreach ($seatStates as $seatState) {
                        $seat = $seatState->seat;
                        if ($seat && $showtime) {
                            // Tính giá vé từ base price và modifier
                            $ticketPrice = $showtime->base_price * ($seat->seatType->price_modifier ?? 1.0);
                            
                            $selectedSeatsInfo[] = [
                                'seat_id' => $seat->id,
                                'row_char' => $seat->row_char,
                                'seat_number' => $seat->seat_number,
                                'price' => $ticketPrice,
                            ];
                        }
                    }
                }
            }

            $bookingPreviewData = [
                'user_id' => $booking->user_id,
                'showtime_id' => $showtime ? $showtime->id : null,
                'booking_code' => $booking->booking_code,
                'total_amount_before_discount' => $booking->total_amount_before_discount,
                'discount_amount' => $booking->discount_amount,
                'final_amount' => $booking->final_amount,
                'promotion_id' => $booking->promotion_id,
                'payment_method_id' => $booking->payment_method_id,
                'notes' => $booking->notes,
                'items' => []
            ];

            // Thêm booking items nếu có
            foreach ($booking->bookingItems as $item) {
                $bookingPreviewData['items'][] = [
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->price_at_purchase,
                ];
            }

            // Lưu lại vào session
            Session::put('booking_preview', $bookingPreviewData);
            Session::put('selected_seats_info', $selectedSeatsInfo);
            Session::put('is_checkout', true);

            Log::info('Retry payment - restored session data', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'final_amount' => $booking->final_amount
            ]);

            // Clear failed session data only on successful retry setup
            Session::forget(['failed_booking_id', 'booking_info', 'payment_error']);

            // Return view with auto-submit form to POST to VNPay
            return view('client.payment.retry_redirect', [
                'vnpay_url' => route('checkout.vnpay'),
                'final_amount' => $booking->final_amount,
                'retry_booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrying payment: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('client.failed')->with('error', 'Có lỗi xảy ra khi thử lại thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Clear all payment-related session data and redirect to home
     */
    public function clearAndGoHome()
    {
        try {
            // Lấy booking ID từ session
            $bookingId = Session::get('failed_booking_id');
            
            if ($bookingId && Auth::check()) {
                Log::info('Attempting to delete failed booking', ['booking_id' => $bookingId]);
                
                // Lấy thông tin booking
                $booking = Booking::with([
                    'tickets',
                    'bookingItems', 
                    'showtimeSeatStates',
                    'payments',
                    'pointHistory'
                ])->find($bookingId);
                
                if ($booking) {
                    // Kiểm tra quyền sở hữu booking
                    if ($booking->user_id === Auth::id()) {
                        DB::beginTransaction();
                        
                        try {
                            // 1. Xóa các tickets liên quan
                            if ($booking->tickets()->exists()) {
                                $booking->tickets()->delete();
                                Log::info('Deleted tickets for booking', ['booking_id' => $bookingId]);
                            }
                            
                            // 2. Xóa các booking items (đồ ăn/đồ uống)
                            if ($booking->bookingItems()->exists()) {
                                $booking->bookingItems()->delete();
                                Log::info('Deleted booking items for booking', ['booking_id' => $bookingId]);
                            }
                            
                            // 3. Cập nhật trạng thái ghế về available trong showtime_seat_states
                            ShowtimeSeatState::where('booking_id', $bookingId)
                                ->update([
                                    'status' => SeatStatus::Available,
                                    'booking_id' => null,
                                    'locked_until' => null,
                                    'locked_by' => null
                                ]);
                            
                            // 4. Xóa lịch sử điểm nếu có
                            if ($booking->pointHistory()->exists()) {
                                $booking->pointHistory()->delete();
                                Log::info('Deleted point history for booking', ['booking_id' => $bookingId]);
                            }
                            
                            // 5. Xóa payments liên quan
                            if ($booking->payments()->exists()) {
                                $booking->payments()->delete();
                                Log::info('Deleted payments for booking', ['booking_id' => $bookingId]);
                            }
                            
                            // 6. Cuối cùng xóa booking
                            $booking->delete();
                            
                            DB::commit();
                            
                            // Xóa booking attempts nếu có
                            if (class_exists('\App\Services\BookingAttemptService')) {
                                $bookingAttemptService = app(\App\Services\BookingAttemptService::class);
                                $bookingAttemptService->cancelAllActiveAttempts(Auth::id());
                            }
                            
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Error deleting booking data: ' . $e->getMessage(), [
                                'booking_id' => $bookingId,
                                'error' => $e->getTraceAsString()
                            ]);
                        }
                    } else {
                        Log::warning('User trying to delete booking not owned by them', [
                            'user_id' => Auth::id(),
                            'booking_user_id' => $booking->user_id,
                            'booking_id' => $bookingId
                        ]);
                    }
                }
            }
            
            // Xóa tất cả session data liên quan đến payment và booking
            Session::forget([
                'failed_booking_id',
                'booking_info', 
                'payment_error',
                'booking_preview',
                'selected_seats_info',
                'is_checkout',
                'is_processing_payment',
                'checkout_data',
                'payment_method_id',
                'current_booking_id'
            ]);
            
            // Xóa tất cả flash messages
            Session::forget(['success', 'error', 'info', 'warning']);
            
        } catch (\Exception $e) {
            Log::error('Error in clearAndGoHome: ' . $e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
        }
        
        Log::info('Cleared all payment-related session data and deleted failed booking, redirecting to home');
        
        return redirect()->route('client.home');
    }

    /**
     * Debug method to check session data
     */
    public function debug()
    {
        if (!config('app.debug')) {
            return response('Debug mode is disabled', 403);
        }
        
        return response()->json([
            'session_data' => [
                'failed_booking_id' => Session::get('failed_booking_id'),
                'booking_info' => Session::get('booking_info'),
                'payment_error' => Session::get('payment_error'),
                'all_session' => Session::all()
            ],
            'auth_user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'email' => Auth::user()->email ?? 'N/A'
            ] : null
        ]);
    }

    /**
     * Get user-friendly error message based on error code
     */
    private function getErrorMessage($errorCode)
    {
        $errorMessages = [
            'PAYMENT_CANCELLED' => 'Bạn đã hủy giao dịch thanh toán.',
            'INSUFFICIENT_FUNDS' => 'Tài khoản không đủ số dư để thực hiện giao dịch.',
            'CARD_EXPIRED' => 'Thẻ của bạn đã hết hạn.',
            'INVALID_CARD' => 'Thông tin thẻ không hợp lệ.',
            'NETWORK_ERROR' => 'Lỗi kết nối mạng. Vui lòng thử lại sau.',
            'BANK_ERROR' => 'Lỗi từ ngân hàng. Vui lòng liên hệ ngân hàng của bạn.',
            'TIMEOUT' => 'Giao dịch đã hết thời gian chờ.',
            'PAYMENT_FAILED' => 'Đã xảy ra lỗi trong quá trình thanh toán.',
        ];

        return $errorMessages[$errorCode] ?? 'Đã xảy ra lỗi không xác định trong quá trình thanh toán.';
    }
}
