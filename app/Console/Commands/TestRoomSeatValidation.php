<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Services\RoomSeatValidationService;
use Illuminate\Console\Command;

class TestRoomSeatValidation extends Command
{
    protected $signature = 'test:room-seat-validation {room_id}';
    protected $description = 'Test room seat validation service';

    public function handle()
    {
        $roomId = $this->argument('room_id');
        $room = Room::with('roomType')->findOrFail($roomId);
        
        $validationService = new RoomSeatValidationService();
        
        $this->info("=== TESTING ROOM: {$room->name} (Type: {$room->roomType->name}) ===");
        
        // 1. Hiển thị seat types được phép
        $allowedSeatTypes = $validationService->getAllowedSeatTypesForRoom($room);
        $this->info("\n✅ Allowed Seat Types:");
        foreach ($allowedSeatTypes as $seatType) {
            $constraints = $validationService->getSeatTypeConstraints($room, $seatType->id);
            $required = $constraints->is_required ? ' (Required)' : '';
            $this->line("  - {$seatType->name}: {$constraints->min_percentage}%-{$constraints->max_percentage}%{$required}");
        }
        
        // 2. Hiển thị cấu hình được đề xuất
        $recommended = $validationService->getRecommendedSeatConfiguration($room);
        $this->info("\n💡 Recommended Configuration:");
        foreach ($recommended as $seatTypeId => $config) {
            $this->line("  - {$config['seat_type']->name}: {$config['suggested_percentage']}%");
        }
        
        // 3. Test validation với một số kịch bản
        $this->info("\n🧪 Testing Validation Scenarios:");
        
        // Kịch bản 1: Cấu hình hợp lệ - đảm bảo tổng = 100%
        $validConfig = [];
        $totalRecommended = 0;
        foreach ($recommended as $seatTypeId => $config) {
            $validConfig[$seatTypeId] = $config['suggested_percentage'];
            $totalRecommended += $config['suggested_percentage'];
        }
        
        // Điều chỉnh để tổng = 100%
        if ($totalRecommended != 100 && count($validConfig) > 0) {
            $firstKey = array_key_first($validConfig);
            $validConfig[$firstKey] = $validConfig[$firstKey] + (100 - $totalRecommended);
        }
        
        $errors = $validationService->validateSeatPercentages($room, $validConfig);
        if (empty($errors)) {
            $this->info("  ✅ Valid config test: PASSED");
        } else {
            $this->error("  ❌ Valid config test: FAILED");
            foreach ($errors as $error) {
                $this->line("    - $error");
            }
        }
        
        // Kịch bản 2: Không đủ 100%
        $invalidConfig = $validConfig;
        if (count($invalidConfig) > 0) {
            $firstKey = array_key_first($invalidConfig);
            $invalidConfig[$firstKey] = $invalidConfig[$firstKey] - 10;
        }
        
        $errors = $validationService->validateSeatPercentages($room, $invalidConfig);
        if (!empty($errors)) {
            $this->info("  ✅ Invalid percentage test: PASSED");
        } else {
            $this->error("  ❌ Invalid percentage test: FAILED");
        }
        
        // 4. Tính toán số ghế cần thiết
        $seatCalculation = $validationService->calculateRequiredSeats($room, $validConfig);
        $this->info("\n📊 Seat Calculation:");
        $this->line("  Room Capacity: {$room->capacity}");
        foreach ($seatCalculation['required'] as $seatTypeId => $required) {
            $existing = $seatCalculation['existing'][$seatTypeId] ?? 0;
            $needed = $seatCalculation['needed'][$seatTypeId] ?? 0;
            $seatType = $allowedSeatTypes->firstWhere('id', $seatTypeId);
            $this->line("  - {$seatType->name}: Required: {$required}, Existing: {$existing}, Needed: {$needed}");
        }
        
        $this->info("\n✨ Test completed!");
    }
}
