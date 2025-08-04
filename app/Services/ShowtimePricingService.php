<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Showtime;

class ShowtimePricingService
{
    /**
     * Tính base_price cho showtime dựa trên loại phòng
     */
    public function calculateBasePriceForRoom(Room $room): float
    {
        // Load roomType nếu chưa được load
        if (!$room->relationLoaded('roomType')) {
            $room->load('roomType');
        }
        
        return $room->roomType->base_price ?? $this->getDefaultBasePrice();
    }
    
    /**
     * Lấy giá cơ bản mặc định
     */
    public function getDefaultBasePrice(): float
    {
        return 100000; // 100,000 VNĐ
    }
    
    /**
     * Lấy thông tin giá theo loại phòng
     */
    public function getRoomTypePricing(): array
    {
        return RoomType::where('status', 'active')
            ->select('id', 'name', 'base_price', 'description')
            ->orderBy('base_price')
            ->get()
            ->toArray();
    }
    
    /**
     * Cập nhật base_price cho showtime dựa trên room type
     */
    public function updateShowtimeBasePrice(Showtime $showtime): bool
    {
        if (!$showtime->relationLoaded('room.roomType')) {
            $showtime->load('room.roomType');
        }
        
        $newBasePrice = $this->calculateBasePriceForRoom($showtime->room);
        
        if ($showtime->base_price !== $newBasePrice) {
            $showtime->base_price = $newBasePrice;
            return $showtime->save();
        }
        
        return true;
    }
    
    /**
     * Lấy thông tin chi tiết về pricing của một showtime
     */
    public function getShowtimePricingInfo(Showtime $showtime): array
    {
        if (!$showtime->relationLoaded('room.roomType')) {
            $showtime->load('room.roomType');
        }
        
        return [
            'showtime_id' => $showtime->id,
            'room_name' => $showtime->room->name,
            'room_type' => $showtime->room->roomType->name ?? 'Unknown',
            'room_type_base_price' => $showtime->room->roomType->base_price ?? $this->getDefaultBasePrice(),
            'current_base_price' => $showtime->base_price,
            'price_difference' => $showtime->base_price - ($showtime->room->roomType->base_price ?? $this->getDefaultBasePrice()),
        ];
    }
}
