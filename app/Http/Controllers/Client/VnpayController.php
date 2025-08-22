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
    
    // dd($request->all());
    $data = $request->all();
    $final_amount = $data['final_amount'];
    $code_cart = rand(00, 9999);
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return'); // Đường dẫn trả về sau khi thanh toán thành công
    
    Log::info('VNPay Return URL:', ['return_url' => $vnp_Returnurl]);
    $vnp_TmnCode = "MIXLC4YW"; //Mã website tại VNPAY 
    $vnp_HashSecret = "NX3ZCRHQUHCZZO6CYTWKQG1URUCNBFXW"; //Chuỗi bí mật

    $vnp_TxnRef = $code_cart; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    $vnp_OrderInfo = 'Thanh toán đơn hàng test';
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

                if ($promotionId) {
                    $hasUsedPromotion = Booking::where('user_id', $bookingData['user_id'])
                        ->where('promotion_id', $promotionId)
                        ->where('status', 'confirmed')
                        ->exists();

                    if ($hasUsedPromotion) {
                        DB::rollBack();
                        return redirect()->route('client.failed')->with('error', 'Mã giảm giá này đã được sử dụng trong đơn hàng trước đó.');
                    }
                }

                // 1. Tạo bản ghi trong bảng bookings
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

                // 3. Cộng điểm thưởng cho người dùng
                $user = $booking->user;
                if (!$user) {
                    \Log::error("User not found for booking ID: {$booking->id}, User ID: {$booking->user_id}");
                    throw new \Exception('Không tìm thấy người dùng.');
                }

                $pointsToAdd = max(1, floor($booking->final_amount / 10000));
                \Log::info("Points to add: {$pointsToAdd}, Booking ID: {$booking->id}, User ID: {$user->id}");

                if ($pointsToAdd > 0 && !PointHistory::where('booking_id', $booking->id)->exists()) {
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

                    \Log::info("Points added for user ID: {$user->id}, Booking ID: {$booking->id}, Points: {$pointsToAdd}");
                } else {
                    \Log::warning("Points not added. Points: {$pointsToAdd}, Existing history: " . (PointHistory::where('booking_id', $booking->id)->exists() ? 'Yes' : 'No'));
                }

                // 4. Lấy showtime từ ghế đã đặt và tạo tickets
                $selectedSeatInfos = session('selected_seats_info', []);

                // Log selected_seats_info để debug
                Log::info('Selected seats info:', $selectedSeatInfos);
                
                if (empty($selectedSeatInfos)) {
                    throw new \Exception('Không tìm thấy thông tin ghế đã chọn.');
                }

                // Lấy showtime_id từ showtime_seat_states của ghế đầu tiên
                $firstSeatId = $selectedSeatInfos[0]['seat_id'];
                $seatState = ShowtimeSeatState::where('seat_id', $firstSeatId)
                    ->where('status', SeatStatus::Reserved)
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
                if (!$seatState) {
                    throw new \Exception('Không tìm thấy trạng thái ghế đã đặt.');
                }
                
                $showtime = Showtime::findOrFail($seatState->showtime_id);
                Log::info('Using existing showtime ID: ' . $showtime->id);

                // 5. Tạo bản ghi trong bảng tickets và cập nhật showtime_seat_states
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

                // 6. Tạo bản ghi trong bảng booking_items
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

                        $variant = ProductVariant::find($item['product_variant_id']);
                        if ($variant) {
                            $variant->stock_quantity = max(0, $variant->stock_quantity - (int)$item['quantity']);
                            $variant->save();
                        }
                    }
                }

                // 7. Mark booking attempt as completed
                $firstSeatState = ShowtimeSeatState::where('booking_id', $booking->id)->first();
                if ($firstSeatState) {
                    $this->bookingAttemptService->completeAttempt($booking->user_id, $firstSeatState->showtime_id);
                }

                DB::commit();
                
                // Fire event để gửi email xác nhận booking
                event(new BookingConfirmed($booking));
                
                session()->forget(['booking_preview', 'selected_seats_info']);

                return redirect()->route('client.success')->with('success', 'Thanh toán thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('VNPay payment processing failed: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công: ' . $e->getMessage());
            }
        } else {
            // Log khi response code không phải 00
            Log::warning('VNPay payment failed with response code: ' . $vnp_ResponseCode, [
                'all_params' => $request->all()
            ]);
        }

        return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công! Mã lỗi: ' . $vnp_ResponseCode);
    }
}