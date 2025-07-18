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
use App\Enums\TicketStatus;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\ProductVariant;
use App\Models\ShowtimeSeatState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class VnpayController extends Controller
{
    public function redirectToVnpay(Request $request)
    {
        $data = $request->all();
        $final_amount = $data['final_amount'];
        $code_cart = rand(00, 9999);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "MIXLC4YW";
        $vnp_HashSecret = "NX3ZCRHQUHCZZO6CYTWKQG1URUCNBFXW";

        $vnp_TxnRef = $code_cart;
        $vnp_OrderInfo = 'Thanh toán đơn hàng test';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (int)$final_amount * 100;
        $vnp_Locale = 'vn';
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
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
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
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $vnp_Amount = $request->get('vnp_Amount') / 100;
        $vnp_TransactionNo = $request->get('vnp_TransactionNo');

        if ($vnp_ResponseCode == '00') {
            DB::beginTransaction();
            try {
                $bookingData = session('booking_preview');

                // Log bookingData để debug
                Log::info('Booking data:', $bookingData);

                // Lấy promotion_id từ promotion_code hoặc discount_amount
                $promotionId = null;
                $discountAmount = (float) ($bookingData['discount_amount'] ?? 0);

              if (!empty($bookingData['promotion_id'])) {
                $promotion = Promotion::where('id', (int) $bookingData['promotion_id'])
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first();
                if ($promotion) {
                      $promotionId = $promotion->id;
                      // Kiểm tra discount_amount khớp với promotion
                      if ($promotion->discount_type == 'fixed' && $promotion->discount_value != $discountAmount) {
                          Log::warning('Discount amount mismatch:', [
                              'promotion_id' => $bookingData['promotion_id'],
                              'promotion_discount_value' => $promotion->discount_value,
                              'booking_discount_amount' => $discountAmount,
                          ]);
                          // Có thể đặt discount_amount theo promotion nếu cần
                          $discountAmount = $promotion->discount_value;
                      }
                  } else {
                      Log::warning('Invalid promotion_id in vnpayReturn:', [
                          'promotion_id' => $bookingData['promotion_id'],
                      ]);
                  }
              }

            // Nếu không tìm thấy promotion qua code hoặc ID, thử tìm theo discount_amount
            if (!$promotionId && $discountAmount > 0) {
                $promotion = Promotion::where('discount_type', 'fixed')
                    ->where('discount_value', $discountAmount)
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first();
                if ($promotion) {
                    $promotionId = $promotion->id;
                    Log::info('Found promotion by discount_amount:', [
                        'promotion_id' => $promotionId,
                        'discount_amount' => $discountAmount,
                    ]);
                } else {
                    Log::warning('No matching promotion found for discount_amount:', [
                        'discount_amount' => $discountAmount,
                    ]);
                }
            }

                // Log để kiểm tra promotion
                Log::info('Final promotion_id to be saved:', [
                    'promotion_id' => $promotionId,
                    'discount_amount' => $discountAmount,
                ]);

                $booking = Booking::create([
                    'user_id' => (int) $bookingData['user_id'],
                    'booking_code' => $bookingData['booking_code'],
                    'total_amount_before_discount' => $bookingData['total_amount_before_discount'] ?? 0,
                    'discount_amount' => $discountAmount,
                    'final_amount' => (float) $bookingData['final_amount'],
                    'promotion_id' => $promotionId,
                    'payment_method_id' => (int) $bookingData['payment_method_id'],
                    'status' => BookingStatus::Confirmed,
                    'notes' => $bookingData['notes'],
                ]);

                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method_id' => $booking->payment_method_id ?? 1,
                    'amount' => $vnp_Amount,
                    'transaction_id_gateway' => $vnp_TransactionNo,
                    'status' => 'completed',
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
                    'base_price' => $bookingData['base_price'] ?? 100000,
                    'status' => 'scheduled',
                ]);

                $selectedSeatInfos = session('selected_seats_info', []);

                // Log selected_seats_info để debug
                Log::info('Selected seats info:', $selectedSeatInfos);

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

                DB::commit();
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
        }

        return redirect()->route('client.failed')->with('error', 'Thanh toán không thành công!');
    }
}