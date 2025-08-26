<?php

namespace App\Services;

use App\Models\BookingItem;
use App\Enums\ProductStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FoodScanService
{
    /**
     * Quét QR cho đồ ăn/uống và cập nhật trạng thái
     */
    public function scanFoodByCode($bookingItemId): array
    {
        try {
            DB::beginTransaction();

            // Cast về integer để đảm bảo tương thích
            $bookingItemId = (int) $bookingItemId;
            $bookingItem = BookingItem::find($bookingItemId);

            if (!$bookingItem) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đồ ăn/uống với mã: ' . $bookingItemId
                ];
            }

            // Kiểm tra trạng thái
            $currentStatus = is_object($bookingItem->product_status) ? $bookingItem->product_status->value : $bookingItem->product_status;

            if ($currentStatus === 'valid') {
                // Nếu trạng thái là valid -> chuyển sang checked
                $bookingItem->update([
                    'product_status' => ProductStatus::Checked,
                    'scanned_by' => Auth::user()->id ?? null
                ]);
                $message = 'Quét đồ ăn/uống thành công! Đã kiểm tra.';
            } elseif ($currentStatus === 'checked') {
                // Nếu trạng thái là checked -> chuyển sang used
                $bookingItem->update([
                    'product_status' => ProductStatus::Used,
                    'used_at' => now(),
                    'scanned_by' => Auth::user()->id ?? null
                ]);
                $message = 'Quét đồ ăn/uống thành công! Đã sử dụng.';
            } elseif ($currentStatus === 'cancelled') {
                // Cho phép lấy đồ ăn đã bị hủy (quá hạn 1 ngày) -> chuyển sang used
                $bookingItem->update([
                    'product_status' => ProductStatus::Used,
                    'used_at' => now(),
                    'scanned_by' => Auth::user()->id ?? null
                ]);
                $message = 'Quét đồ ăn/uống thành công! Đồ ăn đã quá hạn nhưng vẫn được phép lấy.';
            } elseif ($currentStatus === 'used') {
                return [
                    'success' => false,
                    'message' => 'Đồ ăn/uống đã được sử dụng trước đó.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Trạng thái đồ ăn/uống không hợp lệ.'
                ];
            }
            $bookingItem->refresh();

            DB::commit();

            return [
                'success' => true,
                'message' => $message,
                'data' => [
                    'booking_item_id' => $bookingItem->id,
                    'product_status' => $bookingItem->product_status,
                    'used_at' => $bookingItem->used_at,
                    'product_name' => $bookingItem->productVariant?->product?->name ?? '',
                    'quantity' => $bookingItem->quantity,
                    'price' => $bookingItem->price_at_purchase,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi quét đồ ăn/uống: ' . $e->getMessage()
            ];
        }
    }
}
