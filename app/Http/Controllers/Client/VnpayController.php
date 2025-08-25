<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Seat;
use App\Models\Movie;
use App\Models\Point;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Enums\SeatStatus;
use App\Enums\TicketStatus;
use App\Models\BookingItem;
use App\Enums\BookingStatus;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ShowtimeSeatState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\BookingConfirmed;
use App\Services\BookingAttemptService;

class VnpayController extends Controller
{
    protected $bookingAttemptService;

    public function __construct(BookingAttemptService $bookingAttemptService)
    {
        $this->bookingAttemptService = $bookingAttemptService;
    }
  public function redirectToVnpay(Request $request)
  {
    // Log request data để debug
    Log::info('VNPay Redirect Request:', $request->all());
    
    // Đánh dấu đang xử lý thanh toán
    session(['is_processing_payment' => true]);
    
    // Kiểm tra nếu đây là retry payment
    $retryBookingId = $request->get('retry_booking_id');
    $bookingId = null;
    
    if ($retryBookingId) {
        Log::info('Processing retry payment for booking ID: ' . $retryBookingId);
        $bookingId = $retryBookingId;
    } else {
        // Kiểm tra xem có booking hiện có chưa, nếu có thì sử dụng lại
        try {
            $bookingData = session('booking_preview');
            $existingBookingId = session('current_booking_id');
            
            if ($bookingData) {
                $promotionId = $bookingData['promotion_id'] ?? null;
                if ($promotionId === '' || $promotionId === '0' || $promotionId === 0) {
                    $promotionId = null;
                } else if ($promotionId) {
                    $promotionId = (int) $promotionId;
                }

                // Kiểm tra xem có booking hiện có không (pending)
                $existingBooking = null;
                if ($existingBookingId) {
                    $existingBooking = Booking::where('id', $existingBookingId)
                        ->where('status', BookingStatus::Pending)
                        ->first();
                }

                if ($existingBooking) {
                    // Cập nhật booking hiện có thay vì tạo mới
                    $existingBooking->update([
                        'total_amount_before_discount' => $bookingData['total_amount_before_discount'] ?? 0,
                        'discount_amount' => (float) $bookingData['discount_amount'] ?? 0,
                        'final_amount' => (float) $bookingData['final_amount'],
                        'promotion_id' => $promotionId,
                        'payment_method_id' => (int) $bookingData['payment_method_id'],
                        'status' => BookingStatus::Pending,
                        'notes' => $bookingData['notes'],
                        'updated_at' => now(),
                    ]);
                    
                    $bookingId = $existingBooking->id;
                    Log::info('Updated existing booking: ' . $bookingId);
                } else {
                    // Tạo booking mới nếu chưa có booking nào
                    $pendingBooking = Booking::create([
                        'user_id' => (int) $bookingData['user_id'],
                        'booking_code' => $bookingData['booking_code'],
                        'total_amount_before_discount' => $bookingData['total_amount_before_discount'] ?? 0,
                        'discount_amount' => (float) $bookingData['discount_amount'] ?? 0,
                        'final_amount' => (float) $bookingData['final_amount'],
                        'promotion_id' => $promotionId,
                        'payment_method_id' => (int) $bookingData['payment_method_id'],
                        'status' => BookingStatus::Pending,
                        'notes' => $bookingData['notes'],
                    ]);
                    
                    $bookingId = $pendingBooking->id;
                    Log::info('Created new pending booking: ' . $bookingId);
                    
                    // Lưu booking ID vào session để sử dụng sau này
                    session(['current_booking_id' => $bookingId]);
                }

                // Cập nhật showtime_seat_states cho booking từ selected_seats_info
                $selectedSeatsInfo = session('selected_seats_info', []);
                $showtimeId = $bookingData['showtime_id'] ?? null;
                
                if (!empty($selectedSeatsInfo) && $showtimeId) {
                    foreach ($selectedSeatsInfo as $seatInfo) {
                        ShowtimeSeatState::updateOrCreate(
                            [
                                'showtime_id' => $showtimeId,
                                'seat_id' => $seatInfo['seat_id'],
                            ],
                            [
                                'status' => SeatStatus::Reserved,
                                'booking_id' => $bookingId,
                                'locked_by' => session()->getId(),
                                'locked_until' => now()->addMinutes(10), // 10 phút để thanh toán
                            ]
                        );
                    }
                }

                // Tạo booking_items ngay khi tạo booking để đảm bảo data có sẵn kể cả khi thanh toán thất bại
                $booking = Booking::find($bookingId);
                if ($booking && !$booking->bookingItems()->exists()) {
                    $items = is_string($bookingData['items'] ?? '')
                        ? json_decode($bookingData['items'], true)
                        : ($bookingData['items'] ?? []);
                    
                    if (is_array($items) && !empty($items)) {
                        foreach ($items as $item) {
                            BookingItem::create([
                                'booking_id' => $booking->id,
                                'product_variant_id' => $item['product_variant_id'],
                                'quantity' => $item['quantity'],
                                'price_at_purchase' => $item['price_at_purchase'],
                            ]);
                        }
                        Log::info('Created booking items for pending booking: ' . $booking->id);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing booking: ' . $e->getMessage());
        }
    }
    
    $data = $request->all();
    $final_amount = $data['final_amount'];
    
    // Sử dụng booking ID hiện có để tạo code cart
    if ($bookingId) {
        if ($retryBookingId) {
            $code_cart = 'RETRY_' . $bookingId . '_' . rand(1000, 9999);
        } else {
            $code_cart = 'BOOKING_' . $bookingId . '_' . rand(1000, 9999);
        }
    } else {
        $code_cart = 'TEMP_' . rand(100000, 999999); // Fallback case
    }
    
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return'); // Đường dẫn trả về sau khi thanh toán thành công
    
    Log::info('VNPay Return URL:', ['return_url' => $vnp_Returnurl]);
    $vnp_TmnCode = "MIXLC4YW"; //Mã website tại VNPAY 
    $vnp_HashSecret = "NX3ZCRHQUHCZZO6CYTWKQG1URUCNBFXW"; //Chuỗi bí mật

    $vnp_TxnRef = $code_cart; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    $vnp_OrderInfo = $bookingId ? "Thanh toán cho booking #{$bookingId}" : 'Thanh toán đơn hàng';
    $vnp_OrderType = 'billpayment';
    $vnp_Amount = (int)$final_amount * 100; // Số tiền cần thanh toán, nhân với 100 để chuyển sang đơn vị đồng
    $vnp_Locale = 'vn';
    // $vnp_BankCode = 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
      "vnp_Version" => "2.1.0",
      "vnp_TmnCode" => $vnp_TmnCode,
      "vnp_Amount" => $vnp_Amount,
      "vnp_Command" => "pay",
      "vnp_CreateDate" => date('YmdHis'),
      "vnp_CurrCode" => "VND",
      "vnp_IpAddr" => $vnp_IpAddr,
      "vnp_Locale" => $vnp_Locale,
      "vnp_OrderInfo" => $vnp_OrderInfo,
      "vnp_OrderType" => $vnp_OrderType,
      "vnp_ReturnUrl" => $vnp_Returnurl,
      "vnp_TxnRef" => $vnp_TxnRef,

    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
      $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
      $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    }

    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
      if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
      } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
      }
      $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
      $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
      $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    $returnData = array(
      'code' => '00',
      'message' => 'success',
      'data' => $vnp_Url
    );
    if (isset($_POST['redirect'])) {
      header('Location: ' . $vnp_Url);
      die();
    } else {
      echo json_encode($returnData);
    }
  }


    public function vnpayReturn(Request $request)
    {
        // Log toàn bộ request từ VNPay
        Log::info('VNPay Return Request:', $request->all());
        
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $vnp_Amount = $request->get('vnp_Amount') / 100;
        $vnp_TransactionNo = $request->get('vnp_TransactionNo');

        Log::info('VNPay Response Details:', [
            'response_code' => $vnp_ResponseCode,
            'txn_ref' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'transaction_no' => $vnp_TransactionNo
        ]);

        if ($vnp_ResponseCode == '00') {
            DB::beginTransaction();
            try {
                $bookingData = session('booking_preview');

                // Kiểm tra xem có dữ liệu booking không
                if (empty($bookingData)) {
                    Log::error('Booking data not found in session');
                    throw new \Exception('Không tìm thấy thông tin đặt vé trong session.');
                }

                // Log bookingData để debug
                Log::info('Booking data:', $bookingData);

                // Kiểm tra xem đây có phải là retry payment không
                $isRetryPayment = strpos($vnp_TxnRef, 'RETRY_') === 0;
                $existingBookingId = null;
                
                if ($isRetryPayment) {
                    // Extract booking ID từ TxnRef (format: RETRY_123_4567)
                    preg_match('/RETRY_(\d+)_/', $vnp_TxnRef, $matches);
                    if (isset($matches[1])) {
                        $existingBookingId = $matches[1];
                        Log::info('Retry payment detected for existing booking: ' . $existingBookingId);
                    }
                } else {
                    // Check nếu có booking được tạo từ redirectToVnpay
                    preg_match('/BOOKING_(\d+)_/', $vnp_TxnRef, $matches);
                    if (isset($matches[1])) {
                        $existingBookingId = $matches[1];
                        Log::info('Found existing pending booking: ' . $existingBookingId);
                    } else {
                        // Fallback: check session
                        $existingBookingId = session('current_booking_id');
                        if ($existingBookingId) {
                            Log::info('Using booking ID from session: ' . $existingBookingId);
                        }
                    }
                }

                $promotionId = $bookingData['promotion_id'] ?? null;

                // Xử lý promotion_id - đảm bảo nó là integer hoặc null
                if ($promotionId === '' || $promotionId === '0' || $promotionId === 0) {
                    $promotionId = null;
                } else if ($promotionId) {
                    $promotionId = (int) $promotionId;
                }

                Log::info('VnpayController - Final promotion_id to be saved:', [
                    'original' => $bookingData['promotion_id'] ?? 'not_set',
                    'processed' => $promotionId,
                    'type' => gettype($promotionId)
                ]);

                if ($promotionId && !$isRetryPayment) {
                    $hasUsedPromotion = Booking::where('user_id', $bookingData['user_id'])
                        ->where('promotion_id', $promotionId)
                        ->where('status', 'confirmed')
                        ->exists();

                    if ($hasUsedPromotion) {
                        DB::rollBack();
                        return redirect()->route('client.failed')->with('error', 'Mã giảm giá này đã được sử dụng trong đơn hàng trước đó.');
                    }
                }

                // 1. Tạo hoặc cập nhật bản ghi trong bảng bookings
                if ($existingBookingId) {
                    // Cập nhật booking hiện có từ pending/failed thành confirmed
                    $booking = Booking::findOrFail($existingBookingId);
                    
                    $booking->update([
                        'status' => BookingStatus::Confirmed,
                        'updated_at' => now(),
                    ]);
                    Log::info('Updated existing booking to confirmed: ' . $booking->id);
                    
                    // Kiểm tra xem đây có phải là booking đã có tickets chưa (retry case)
                    $hasTickets = $booking->tickets()->exists();
                } else {
                    // Tạo booking mới (trường hợp fallback)
                    $booking = Booking::create([
                        'user_id' => (int) $bookingData['user_id'],
                        'booking_code' => $bookingData['booking_code'],
                        'total_amount_before_discount' => $bookingData['total_amount_before_discount'] ?? 0,
                        'discount_amount' => (float) $bookingData['discount_amount'] ?? 0,
                        'final_amount' => (float) $bookingData['final_amount'],
                        'promotion_id' => $promotionId,
                        'payment_method_id' => (int) $bookingData['payment_method_id'],
                        'status' => BookingStatus::Confirmed,
                        'notes' => $bookingData['notes'],
                    ]);
                    Log::info('Created new booking: ' . $booking->id);
                    $hasTickets = false;
                }

                // 2. Tạo bản ghi trong bảng payments
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method_id' => $booking->payment_method_id ?? 1,
                    'amount' => $vnp_Amount,
                    'transaction_id_gateway' => $vnp_TransactionNo,
                    'status' => 'completed',
                    'payment_details' => json_encode($request->all()),
                    'paid_at' => now(),
                ]);

                // 3. Cộng điểm thưởng cho người dùng (chỉ cho booking mới hoặc booking chưa có điểm)
                $hasPointHistory = PointHistory::where('booking_id', $booking->id)->exists();
                if (!$hasPointHistory) {
                    $user = $booking->user;
                    if (!$user) {
                        Log::error("User not found for booking ID: {$booking->id}, User ID: {$booking->user_id}");
                        throw new \Exception('Không tìm thấy người dùng.');
                    }

                    $pointsToAdd = max(1, floor($booking->final_amount / 10000));
                    Log::info("Points to add: {$pointsToAdd}, Booking ID: {$booking->id}, User ID: {$user->id}");

                    if ($pointsToAdd > 0) {
                        $point = Point::firstOrCreate(
                            ['user_id' => $user->id],
                            ['points_expiry_date' => now()->addYear(), 'created_at' => now(), 'updated_at' => now()]
                        );
                        $point->total_points = ($point->total_points ?? 0) + $pointsToAdd;
                        $point->save();

                        PointHistory::create([
                            'user_id' => $user->id,
                            'booking_id' => $booking->id,
                            'points_change' => $pointsToAdd,
                            'reason_type' => 'earned',
                            'description' => 'Cộng điểm cho đơn hàng #' . $booking->id,
                            'created_at' => now(),
                        ]);

                        Log::info("Points added for user ID: {$user->id}, Booking ID: {$booking->id}, Points: {$pointsToAdd}");
                    }
                } else {
                    Log::info("Skipped adding points for booking with existing point history: {$booking->id}");
                }

                // 4. Lấy thông tin ghế từ showtime_seat_states thay vì session/notes
                $selectedSeatInfos = [];

                // Ưu tiên lấy từ showtime_seat_states của booking hiện tại
                if ($existingBookingId) {
                    $seatStates = ShowtimeSeatState::with(['seat.seatType', 'showtime'])
                        ->where('booking_id', $existingBookingId)
                        ->get();
                    
                    if ($seatStates->isNotEmpty()) {
                        foreach ($seatStates as $seatState) {
                            $seat = $seatState->seat;
                            $showtime = $seatState->showtime;
                            
                            if ($seat && $showtime) {
                                // Tính giá vé từ base price và modifier
                                $ticketPrice = $showtime->base_price * ($seat->seatType->price_modifier ?? 1.0);
                                
                                $selectedSeatInfos[] = [
                                    'seat_id' => $seat->id,
                                    'row_char' => $seat->row_char,
                                    'seat_number' => $seat->seat_number,
                                    'price' => $ticketPrice,
                                ];
                            }
                        }
                        Log::info('Retrieved seat info from showtime_seat_states: ' . count($selectedSeatInfos) . ' seats');
                    }
                }

                // Fallback: lấy từ session nếu chưa có (booking mới)
                if (empty($selectedSeatInfos)) {
                    $selectedSeatInfos = session('selected_seats_info', []);
                    if (!empty($selectedSeatInfos)) {
                        Log::info('Retrieved seat info from session: ' . count($selectedSeatInfos) . ' seats');
                    }
                }

                // Fallback cuối cùng: lấy từ tickets nếu có
                if (empty($selectedSeatInfos) && $existingBookingId) {
                    $existingBooking = Booking::with(['tickets.seat', 'tickets.showtime'])->find($existingBookingId);
                    if ($existingBooking && $existingBooking->tickets->isNotEmpty()) {
                        foreach ($existingBooking->tickets as $ticket) {
                            $selectedSeatInfos[] = [
                                'seat_id' => $ticket->seat_id,
                                'row_char' => $ticket->seat->row_char ?? '',
                                'seat_number' => $ticket->seat->seat_number ?? '',
                                'price' => $ticket->price_at_purchase,
                            ];
                        }
                        Log::info('Retrieved seat info from existing tickets: ' . count($selectedSeatInfos) . ' seats');
                    }
                }

                // Log selected_seats_info để debug
                Log::info('Final selected seats info:', $selectedSeatInfos);
                
                if (empty($selectedSeatInfos)) {
                    throw new \Exception('Không tìm thấy thông tin ghế đã chọn. Vui lòng đặt lại vé.');
                }

                // Lấy showtime_id từ seat states hoặc booking data
                $showtimeId = null;
                if ($existingBookingId) {
                    $seatState = ShowtimeSeatState::where('booking_id', $existingBookingId)->first();
                    if ($seatState) {
                        $showtimeId = $seatState->showtime_id;
                        Log::info('Retrieved showtime ID from seat states: ' . $showtimeId);
                    }
                }
                
                if (!$showtimeId) {
                    $showtimeId = $bookingData['showtime_id'] ?? null;
                    if ($showtimeId) {
                        Log::info('Retrieved showtime ID from booking data: ' . $showtimeId);
                    }
                }
                
                if (!$showtimeId) {
                    throw new \Exception('Không tìm thấy thông tin suất chiếu. Vui lòng đặt lại vé.');
                }
                
                $showtime = Showtime::findOrFail($showtimeId);
                Log::info('Using showtime ID: ' . $showtime->id);

                // 5. Tạo bản ghi trong bảng tickets và cập nhật showtime_seat_states (chỉ khi chưa có tickets)
                if (!$hasTickets) {
                    foreach ($selectedSeatInfos as $seatInfo) {
                        $seat = Seat::where('id', $seatInfo['seat_id'])->first();

                        if (!$seat) {
                            throw new \Exception('Không tìm thấy seat ID: ' . $seatInfo['seat_id']);
                        }

                        // Sử dụng giá từ session
                        $ticketPrice = (float) ($seatInfo['price'] ?? $showtime->base_price);

                        // Log để kiểm tra giá vé
                        Log::info('Ticket price calculation:', [
                            'seat_id' => $seat->id,
                            'seat_type_id' => $seat->seat_type_id,
                            'price' => $seatInfo['price'] ?? 'not set',
                            'ticket_price' => $ticketPrice,
                        ]);

                        Ticket::create([
                            'booking_id' => $booking->id,
                            'showtime_id' => $showtime->id,
                            'seat_id' => $seat->id,
                            'ticket_code' => 'TICKET_' . uniqid(),
                            'price_at_purchase' => $ticketPrice,
                            'status' => TicketStatus::Valid,
                        ]);

                        ShowtimeSeatState::where('showtime_id', $showtime->id)
                            ->where('seat_id', $seat->id)
                            ->update([
                                'status' => SeatStatus::Booked,
                                'booking_id' => $booking->id,
                                'locked_by' => null,
                                'locked_until' => null,
                            ]);
                    }
                    Log::info('Created tickets for booking: ' . $booking->id);
                } else {
                    // Đối với booking đã có tickets, chỉ cần cập nhật seat states và ticket status
                    $existingTickets = $booking->tickets;
                    foreach ($existingTickets as $ticket) {
                        // Update ticket status to valid
                        $ticket->update(['status' => TicketStatus::Valid]);
                        
                        // Update seat states
                        ShowtimeSeatState::where('showtime_id', $ticket->showtime_id)
                            ->where('seat_id', $ticket->seat_id)
                            ->update([
                                'status' => SeatStatus::Booked,
                                'booking_id' => $booking->id,
                                'locked_by' => null,
                                'locked_until' => null,
                            ]);
                    }
                    Log::info('Updated existing tickets and seat states for booking: ' . $booking->id);
                }

                // 6. Tạo bản ghi trong bảng booking_items (chỉ khi chưa có booking items) và cập nhật stock khi thanh toán thành công
                $hasBookingItems = $booking->bookingItems()->exists();
                if (!$hasBookingItems) {
                    // Tạo booking items nếu chưa có (trường hợp fallback)
                    $items = is_string($bookingData['items'])
                        ? json_decode($bookingData['items'], true)
                        : ($bookingData['items'] ?? []);
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            BookingItem::create([
                                'booking_id' => $booking->id,
                                'product_variant_id' => $item['product_variant_id'],
                                'quantity' => $item['quantity'],
                                'price_at_purchase' => $item['price_at_purchase'],
                            ]);
                        }
                        Log::info('Created booking items for booking: ' . $booking->id);
                    }
                }

                // Cập nhật stock quantity chỉ khi thanh toán thành công
                foreach ($booking->bookingItems as $bookingItem) {
                    $variant = ProductVariant::find($bookingItem->product_variant_id);
                    if ($variant) {
                        $variant->stock_quantity = max(0, $variant->stock_quantity - (int)$bookingItem->quantity);
                        $variant->save();
                        Log::info('Updated stock for product variant: ' . $variant->id);
                    }
                }

                // 7. Mark booking attempt as completed
                $firstSeatState = ShowtimeSeatState::where('booking_id', $booking->id)->first();
                if ($firstSeatState) {
                    $this->bookingAttemptService->completeAttempt($booking->user_id, $firstSeatState->showtime_id);
                }

                DB::commit();
                
                // Fire event để gửi email xác nhận booking (chỉ cho booking lần đầu confirm)
                $isFirstTimeConfirm = !$hasTickets || !$hasBookingItems;
                if ($isFirstTimeConfirm) {
                    event(new BookingConfirmed($booking));
                }
                
                session()->forget(['booking_preview', 'selected_seats_info', 'is_checkout', 'is_processing_payment', 'current_booking_id']);

                $successMessage = $existingBookingId ? 'Thanh toán thành công!' : 'Thanh toán thành công!';
                return redirect()->route('client.success')->with('success', $successMessage);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('VNPay payment processing failed: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                session()->forget(['is_checkout', 'is_processing_payment']);
                
                // Lưu thông tin booking để hiển thị trên trang failed
                if (isset($booking)) {
                    session()->put('failed_booking_id', $booking->id);
                }
                
                return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công: ' . $e->getMessage());
            }
        } else {
            // Log khi response code không phải 00
            Log::warning('VNPay payment failed with response code: ' . $vnp_ResponseCode, [
                'all_params' => $request->all()
            ]);
            
            // Clear payment session khi thanh toán thất bại
            session()->forget(['is_checkout', 'is_processing_payment']);
            
            // Lấy booking ID từ transaction reference hoặc session
            $bookingId = null;
            if (strpos($vnp_TxnRef, 'BOOKING_') === 0) {
                preg_match('/BOOKING_(\d+)_/', $vnp_TxnRef, $matches);
                if (isset($matches[1])) {
                    $bookingId = $matches[1];
                }
            } elseif (strpos($vnp_TxnRef, 'RETRY_') === 0) {
                preg_match('/RETRY_(\d+)_/', $vnp_TxnRef, $matches);
                if (isset($matches[1])) {
                    $bookingId = $matches[1];
                }
            } else {
                $bookingId = session('current_booking_id');
            }
            
            if ($bookingId) {
                session()->put('failed_booking_id', $bookingId);
                Log::info('Payment failed for booking: ' . $bookingId);
            }
            
            // Lưu thông tin booking preview để hiển thị
            $bookingPreview = session('booking_preview');
            if ($bookingPreview) {
                session()->put('booking_info', [
                    'total_price' => $bookingPreview['final_amount'] ?? ($bookingPreview['total_amount'] ?? 0),
                    'seat_row' => $bookingPreview['selected_seats'][0]['row'] ?? null,
                    'seat_number' => $bookingPreview['selected_seats'][0]['seat_number'] ?? null,
                ]);
            }
        }

        return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công! Mã lỗi: ' . $vnp_ResponseCode);
    }
}