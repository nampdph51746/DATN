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
}
