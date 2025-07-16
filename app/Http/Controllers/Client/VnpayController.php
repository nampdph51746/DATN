<?php

namespace App\Http\Controllers\Client;

use App\Models\Room;
use App\Models\Seat;
use App\Models\Movie;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Enums\SeatStatus;
use App\Models\BookingItem;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ShowtimeSeatState;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VnpayController extends Controller
{
  public function redirectToVnpay(Request $request)
  {
    // dd($request->all());
    $data = $request->all();
    $final_amount = $data['final_amount'];
    $code_cart = rand(00, 9999);
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return'); // Đường dẫn trả về sau khi thanh toán thành công
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
    $vnp_ResponseCode = $request->get('vnp_ResponseCode');
    $vnp_TxnRef = $request->get('vnp_TxnRef'); // booking_code
    $vnp_Amount = $request->get('vnp_Amount') / 100;
    $vnp_TransactionNo = $request->get('vnp_TransactionNo');

    if ($vnp_ResponseCode == '00') {
      DB::beginTransaction();
      try {
        // Lấy dữ liệu session
        $bookingData = session('booking_preview');
        // dd($bookingData);

        // 1. Tạo bản ghi trong bảng bookings
        $booking = Booking::create([
          'user_id' => (int) $bookingData['user_id'],
          'booking_code' => $bookingData['booking_code'],
          'total_amount_before_discount' => $bookingData['total_amount_before_discount'] ?? 0,
          'discount_amount' => (float) $bookingData['discount_amount'] ?? 0,
          'final_amount' => (float) $bookingData['final_amount'],
          'promotion_id' => 526, //$bookingData['promotion_id']
          'payment_method_id' => (int) $bookingData['payment_method_id'],
          'status' => BookingStatus::Confirmed, // CHÍNH XÁC
          'notes' => $bookingData['notes'],
        ]);
        // dd($booking);
        Payment::create([
          'booking_id' => $booking->id,
          'payment_method_id' => $booking->payment_method_id ?? 1,
          'amount' => $vnp_Amount,
          'transaction_id_gateway' => $vnp_TransactionNo,
          'status' => 'pending',
          'payment_details' => json_encode($request->all()),
          'paid_at' => now(),
        ]);

        $movie = Movie::where('name', $bookingData['movie_title'])->firstOrFail();
        $room = Room::where('name', $bookingData['room_name'])->firstOrFail();
        $startTime = $bookingData['showtime'] ? \Carbon\Carbon::parse($bookingData['showtime']) : now();
        $endTime = $startTime->copy()->addMinutes(90);

        $showtime = Showtime::create([
          'movie_id' => $movie->id,
          'room_id' => $room->id,
          'start_time' => $startTime,
          'end_time' => $endTime,
          'base_price' => $vnp_Amount, // hoặc logic động nếu có
          'status' => 'scheduled',
        ]);
        $selectedSeatInfos = session('selected_seats_info', []);

        foreach ($selectedSeatInfos as $seatInfo) {
          $seat = Seat::where('id', $seatInfo['seat_id'])->first();

          if (!$seat) {
            throw new \Exception('Không tìm thấy seat ID: ' . $seatInfo['seat_id']);
          }

          $ticketPrice = $showtime->base_price * ($seatInfo['price_modifier'] ?? 1);

          Ticket::create([
            'booking_id' => $booking->id,
            'showtime_id' => $showtime->id,
            'seat_id' => $seat->id,
            'ticket_code' => 'TICKET_' . uniqid(),
            'price_at_purchase' => 1000000,
            'status' => 'valid',
          ]);

          // Cập nhật trạng thái ghế trong ShowtimeSeatState (nếu cần)
          ShowtimeSeatState::where('showtime_id', $showtime->id)
            ->where('seat_id', $seat->id)
            ->update([
              'status' => SeatStatus::Available,
              'booking_id' => $booking->id,
              'locked_by' => null,
              'locked_until' => null,
            ]);
        }

        // Sau khi tạo $booking
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
                // Trừ tồn kho product_variant
                $variant = ProductVariant::find($item['product_variant_id']);
                if ($variant) {
                    $variant->stock_quantity = max(0, $variant->stock_quantity - (int)$item['quantity']);
                    $variant->save();
                }
            }
        }

        DB::commit();
        session()->forget('booking_preview');

        return redirect()->route('client.success')->with('success', 'Thanh toán thành công!');
      } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage(), $e->getLine(), $e->getFile());
      }
    }

    return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công!');
  }
}
