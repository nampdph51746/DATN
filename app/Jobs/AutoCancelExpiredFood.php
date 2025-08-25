<?php

namespace App\Jobs;

use App\Models\BookingItem;
use App\Enums\ProductStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCancelExpiredFood implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * Tự động hủy đồ ăn sau 1 ngày kể từ khi booking được tạo
     */
    public function handle(): void
    {
        $oneDayAgo = Carbon::now()->subDay();
        
        Log::info("Starting auto-cancel expired food job", [
            'current_time' => Carbon::now()->toDateTimeString(),
            'cutoff_time' => $oneDayAgo->toDateTimeString()
        ]);
        
        // Tìm các booking items có trạng thái valid hoặc checked và đã quá 1 ngày
        $expiredFoodItems = BookingItem::whereIn('product_status', [ProductStatus::Valid, ProductStatus::Checked])
            ->whereHas('booking', function($query) use ($oneDayAgo) {
                $query->where('created_at', '<', $oneDayAgo);
            })
            ->with('booking', 'productVariant.product')
            ->get();

        Log::info("Found expired food items", [
            'count' => $expiredFoodItems->count(),
            'cutoff_time' => $oneDayAgo->toDateTimeString()
        ]);

        $cancelledCount = 0;
        
        foreach ($expiredFoodItems as $item) {
            try {
                // Ghi log trước khi update
                Log::info("Processing expired food item", [
                    'booking_item_id' => $item->id,
                    'current_status' => $item->product_status->value,
                    'booking_created_at' => $item->booking->created_at,
                    'hours_since_booking' => Carbon::parse($item->booking->created_at)->diffInHours(Carbon::now())
                ]);
                
                $item->update([
                    'product_status' => ProductStatus::Cancelled,
                ]);
                $cancelledCount++;
                
                Log::info("Auto cancelled expired food item", [
                    'booking_item_id' => $item->id,
                    'booking_id' => $item->booking_id,
                    'booking_code' => $item->booking->booking_code ?? 'N/A',
                    'product_name' => $item->productVariant?->product?->name ?? 'N/A',
                    'quantity' => $item->quantity,
                    'booking_created_at' => $item->booking->created_at ?? 'N/A',
                    'new_status' => ProductStatus::Cancelled->value
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to cancel expired food item", [
                    'booking_item_id' => $item->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        if ($cancelledCount > 0) {
            Log::info("Auto cancelled {$cancelledCount} expired food items");
        } else {
            Log::info("No expired food items found to cancel");
        }
    }
}