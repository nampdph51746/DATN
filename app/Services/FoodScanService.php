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
    public function scanFoodByCode(string $bookingItemId): array
    {
        try {
            DB::beginTransaction();

            $bookingItem = BookingItem::find($bookingItemId);

            if (!$bookingItem) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy đồ ăn/uống với mã: ' . $bookingItemId
                ];
            }

            // Kiểm tra trạng thái
            if ($bookingItem->product_status === ProductStatus::Checked) {
                return [
                    'success' => false,
                    'message' => 'Đồ ăn/uống đã được quét trước đó.'
                ];
            }

            // Cập nhật trạng thái thành "checked" (KHÔNG cập nhật used_at)
            $bookingItem->update([
                'product_status' => ProductStatus::Checked,
                'scanned_by' => Auth::user()->id ?? null
            ]);
            $bookingItem->refresh();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Quét đồ ăn/uống thành công!',
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