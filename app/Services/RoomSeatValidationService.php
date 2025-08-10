<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\RoomSeatTypeConstraint;
use Illuminate\Support\Collection;

class RoomSeatValidationService
{
    /**
     * Lấy các seat types được phép cho một room type
     */
    public function getAllowedSeatTypesForRoom(Room $room): Collection
    {
        return RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)
            ->with('seatType')
            ->get()
            ->pluck('seatType');
    }

    /**
     * Kiểm tra xem seat type có được phép trong room không
     */
    public function isSeatTypeAllowedInRoom(Room $room, int $seatTypeId): bool
    {
        return RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)
            ->where('seat_type_id', $seatTypeId)
            ->exists();
    }

    /**
     * Lấy constraints cho một seat type trong room
     */
    public function getSeatTypeConstraints(Room $room, int $seatTypeId): ?RoomSeatTypeConstraint
    {
        return RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)
            ->where('seat_type_id', $seatTypeId)
            ->first();
    }

    /**
     * Validate tỷ lệ ghế theo constraints
     */
    public function validateSeatPercentages(Room $room, array $seatPercentages): array
    {
        $errors = [];
        $totalPercentage = array_sum($seatPercentages);

        // Kiểm tra tổng tỷ lệ = 100%
        if (abs($totalPercentage - 100) > 0.01) {
            $errors[] = 'Tổng tỷ lệ ghế phải bằng 100%.';
        }

        // Kiểm tra từng seat type
        foreach ($seatPercentages as $seatTypeId => $percentage) {
            $constraints = $this->getSeatTypeConstraints($room, $seatTypeId);
            
            if (!$constraints) {
                $seatType = SeatType::find($seatTypeId);
                $errors[] = "Loại ghế '{$seatType->name}' không được phép trong phòng {$room->roomType->name}.";
                continue;
            }

            if ($percentage < $constraints->min_percentage) {
                $errors[] = "Loại ghế '{$constraints->seatType->name}' phải có tối thiểu {$constraints->min_percentage}%.";
            }

            if ($percentage > $constraints->max_percentage) {
                $errors[] = "Loại ghế '{$constraints->seatType->name}' không được vượt quá {$constraints->max_percentage}%.";
            }
        }

        // Kiểm tra seat types bắt buộc
        $requiredConstraints = RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)
            ->where('is_required', true)
            ->with('seatType')
            ->get();

        foreach ($requiredConstraints as $constraint) {
            if (!isset($seatPercentages[$constraint->seat_type_id]) || $seatPercentages[$constraint->seat_type_id] <= 0) {
                $errors[] = "Loại ghế '{$constraint->seatType->name}' là bắt buộc trong phòng {$room->roomType->name}.";
            }
        }

        return $errors;
    }

    /**
     * Tính toán số ghế cần thêm cho từng loại theo constraints
     */
    public function calculateRequiredSeats(Room $room, array $seatPercentages): array
    {
        $requiredSeats = [];
        $totalCapacity = $room->capacity;

        foreach ($seatPercentages as $seatTypeId => $percentage) {
            $requiredSeats[$seatTypeId] = (int) round(($percentage / 100) * $totalCapacity);
        }

        // Đếm ghế hiện có
        $existingSeats = Seat::where('room_id', $room->id)
            ->selectRaw('seat_type_id, COUNT(*) as count')
            ->groupBy('seat_type_id')
            ->pluck('count', 'seat_type_id')
            ->toArray();

        // Tính ghế còn thiếu
        $neededSeats = [];
        foreach ($requiredSeats as $seatTypeId => $required) {
            $existing = $existingSeats[$seatTypeId] ?? 0;
            $neededSeats[$seatTypeId] = max(0, $required - $existing);
        }

        return [
            'required' => $requiredSeats,
            'existing' => $existingSeats,
            'needed' => $neededSeats
        ];
    }

    /**
     * Lấy seat types được đề xuất cho room type
     */
    public function getRecommendedSeatConfiguration(Room $room): array
    {
        $constraints = RoomSeatTypeConstraint::where('room_type_id', $room->room_type_id)
            ->with('seatType')
            ->orderBy('is_required', 'desc')
            ->orderBy('min_percentage', 'desc')
            ->get();

        $configuration = [];
        foreach ($constraints as $constraint) {
            // Đề xuất tỷ lệ ở giữa min và max, ưu tiên required types
            $suggestedPercentage = $constraint->is_required 
                ? ($constraint->min_percentage + $constraint->max_percentage) / 2
                : $constraint->min_percentage;
                
            $configuration[$constraint->seat_type_id] = [
                'seat_type' => $constraint->seatType,
                'suggested_percentage' => $suggestedPercentage,
                'min_percentage' => $constraint->min_percentage,
                'max_percentage' => $constraint->max_percentage,
                'is_required' => $constraint->is_required,
            ];
        }

        return $configuration;
    }

    /**
     * Validate room capacity cho couple seats để tránh ghế lẻ
     */
    public function validateRoomCapacityForCoupleSeats(Room $room): array
    {
        $errors = [];
        $suggestions = [];
        
        // Lấy các seat types được phép trong room
        $allowedSeatTypes = $this->getAllowedSeatTypesForRoom($room);
        
        // Kiểm tra xem có couple seats không
        $coupleSeats = $allowedSeatTypes->filter(function ($seatType) {
            return $this->isCoupleSeats($seatType->name);
        });
        
        if ($coupleSeats->isEmpty()) {
            return ['errors' => $errors, 'suggestions' => $suggestions];
        }
        
        // Lấy constraints cho couple seats
        $coupleConstraints = [];
        foreach ($coupleSeats as $seatType) {
            $constraint = $this->getSeatTypeConstraints($room, $seatType->id);
            if ($constraint) {
                $coupleConstraints[] = [
                    'seat_type' => $seatType,
                    'constraint' => $constraint
                ];
            }
        }
        
        if (empty($coupleConstraints)) {
            return ['errors' => $errors, 'suggestions' => $suggestions];
        }
        
        // Tính toán capacity tối ưu cho couple seats
        $capacity = $room->capacity;
        $optimalCapacities = $this->calculateOptimalCapacityForCoupleSeats($capacity, $coupleConstraints);
        
        // Kiểm tra xem capacity hiện tại có phù hợp không
        if (!in_array($capacity, $optimalCapacities)) {
            $errors[] = "Sức chứa phòng ({$capacity}) không phù hợp với ghế đôi theo constraints.";
            
            // Đề xuất capacity gần nhất
            $nearestCapacities = $this->findNearestOptimalCapacities($capacity, $optimalCapacities);
            if (!empty($nearestCapacities)) {
                $suggestions[] = "Đề xuất capacity: " . implode(', ', array_slice($nearestCapacities, 0, 3));
            }
        }
        
        return ['errors' => $errors, 'suggestions' => $suggestions];
    }
    
    /**
     * Kiểm tra xem seat type có phải couple seats không
     */
    private function isCoupleSeats(string $seatTypeName): bool
    {
        $coupleKeywords = ['couple', 'đôi', 'bed', 'sofa'];
        $lowerName = strtolower($seatTypeName);
        
        foreach ($coupleKeywords as $keyword) {
            if (strpos($lowerName, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Tính toán capacity tối ưu cho couple seats
     */
    private function calculateOptimalCapacityForCoupleSeats(int $baseCapacity, array $coupleConstraints): array
    {
        $optimalCapacities = [];
        
        // Dải capacity để kiểm tra (±10 từ base capacity)
        $minCapacity = max(20, $baseCapacity - 10);
        $maxCapacity = min(200, $baseCapacity + 10);
        
        for ($capacity = $minCapacity; $capacity <= $maxCapacity; $capacity++) {
            $isOptimal = true;
            
            foreach ($coupleConstraints as $constraintData) {
                $constraint = $constraintData['constraint'];
                $seatType = $constraintData['seat_type'];
                
                // Tính số ghế couple theo percentage
                $minPercentage = $constraint->min_percentage;
                $maxPercentage = $constraint->max_percentage;
                
                $minCoupleSeats = ceil(($minPercentage / 100) * $capacity);
                $maxCoupleSeats = floor(($maxPercentage / 100) * $capacity);
                
                // Couple seats phải là số chẵn
                if ($minCoupleSeats % 2 !== 0) $minCoupleSeats++;
                if ($maxCoupleSeats % 2 !== 0) $maxCoupleSeats--;
                
                // Kiểm tra xem có thể phân bổ được không
                if ($minCoupleSeats > $maxCoupleSeats || $maxCoupleSeats <= 0) {
                    $isOptimal = false;
                    break;
                }
            }
            
            if ($isOptimal) {
                $optimalCapacities[] = $capacity;
            }
        }
        
        return $optimalCapacities;
    }
    
    /**
     * Tìm capacity gần nhất với capacity hiện tại
     */
    private function findNearestOptimalCapacities(int $currentCapacity, array $optimalCapacities): array
    {
        if (empty($optimalCapacities)) {
            return [];
        }
        
        // Sắp xếp theo độ gần với capacity hiện tại
        usort($optimalCapacities, function ($a, $b) use ($currentCapacity) {
            return abs($a - $currentCapacity) - abs($b - $currentCapacity);
        });
        
        return $optimalCapacities;
    }
}
