<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\RoomSeatConfiguration;
use App\Enums\SeatStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeatManagementService
{
    private $roomSeatValidation;

    public function __construct(RoomSeatValidationService $roomSeatValidation)
    {
        $this->roomSeatValidation = $roomSeatValidation;
    }

    /**
     * Tạo ghế cho phòng với validation nâng cao
     */
    public function createSeatsForRoom(array $data, bool $ignoreConstraints = false): array
    {
        $room = Room::findOrFail($data['room_id']);
        
        // 1. Validate room status và capacity
        $this->validateRoom($room);
        
        // 2. Validate seat type permissions
        $this->validateSeatTypePermissions($room, $data['seat_type_id']);
        
        // 3. Validate seat percentages (có thể bỏ qua nếu ignoreConstraints = true)
        if (!$ignoreConstraints) {
            $this->validateSeatPercentages($room, $data['seat_type_percentages']);
        }
        
        // 4. Calculate seat requirements
        $seatCalculation = $this->calculateSeatRequirements($room, $data);
        
        // 5. Validate seat layout
        $layoutValidation = $this->validateSeatLayout($room, $seatCalculation, $data);
        
        DB::beginTransaction();
        try {
            // 6. Save seat configuration
            $this->saveSeatConfiguration($room, $data['seat_type_percentages']);
            
            // 7. Create seats
            $createdSeats = $this->createSeats($room, $seatCalculation, $data);
            
            // 8. Update statistics
            $nextSeatType = $this->calculateNextSeatType($room, $data['seat_type_percentages']);
            
            DB::commit();
            
            return [
                'success' => true,
                'created_seats' => $createdSeats,
                'next_seat_type' => $nextSeatType,
                'suggestions' => $this->generateSuggestions($room, $nextSeatType),
                'warnings' => $layoutValidation['warnings'] ?? []
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Seat creation failed: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Validate room có thể thêm ghế
     */
    private function validateRoom(Room $room): void
    {
        if ($room->status !== 'active') {
            throw new \InvalidArgumentException('Phòng chiếu không ở trạng thái hoạt động.');
        }

        $existingSeatsCount = $room->seats()->count();
        if ($existingSeatsCount >= $room->capacity) {
            throw new \InvalidArgumentException('Phòng đã đầy ghế.');
        }
    }

    /**
     * Validate seat type có được phép trong room type
     */
    private function validateSeatTypePermissions(Room $room, int $seatTypeId): void
    {
        if (!$this->roomSeatValidation->isSeatTypeAllowedInRoom($room, $seatTypeId)) {
            $seatType = SeatType::find($seatTypeId);
            throw new \InvalidArgumentException(
                "Loại ghế '{$seatType->name}' không được phép trong phòng {$room->roomType->name}."
            );
        }
    }

    /**
     * Validate seat percentages theo business rules
     */
    private function validateSeatPercentages(Room $room, array $seatPercentages): void
    {
        // Chỉ validate các phòng có loại ghế đặc biệt
        $roomType = $room->roomType;
        if (!$roomType) {
            return; // Skip validation nếu không có room type
        }
        
        // Chỉ validate cho các phòng có sweetbox, couple bed, couple sofa
        $specialSeatTypes = ['sweetbox', 'couple bed', 'couple sofa'];
        $hasSpecialSeats = false;
        
        foreach ($seatPercentages as $seatTypeId => $percentage) {
            $seatType = SeatType::find($seatTypeId);
            if ($seatType && in_array(strtolower($seatType->name), $specialSeatTypes)) {
                $hasSpecialSeats = true;
                break;
            }
        }
        
        // Nếu không có loại ghế đặc biệt, skip validation
        if (!$hasSpecialSeats) {
            return;
        }
        
        // Validate capacity cho couple seats
        $capacityValidation = $this->roomSeatValidation->validateRoomCapacityForCoupleSeats($room);
        if (!empty($capacityValidation['errors'])) {
            $errorMessage = implode(' ', $capacityValidation['errors']);
            if (!empty($capacityValidation['suggestions'])) {
                $errorMessage .= ' ' . implode(' ', $capacityValidation['suggestions']);
            }
            throw new \InvalidArgumentException($errorMessage);
        }
        
        // Chỉ validate constraints khi có loại ghế đặc biệt
        $errors = $this->roomSeatValidation->validateSeatPercentages($room, $seatPercentages);
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(' ', $errors));
        }
    }

    /**
     * Tính toán số ghế cần tạo cho từng loại
     */
    private function calculateSeatRequirements(Room $room, array $data): array
    {
        $totalSeats = $room->capacity;
        $requiredSeats = [];
        $existingSeatsByType = [];

        // Tính số ghế yêu cầu theo tỷ lệ
        foreach ($data['seat_type_percentages'] as $seatTypeId => $percentage) {
            $calculatedSeats = (int) round(($percentage / 100) * $totalSeats);
            
            // Điều chỉnh cho ghế đôi
            $seatType = \App\Models\SeatType::find($seatTypeId);
            if ($seatType && $this->isCoupleSeaType($seatType->name)) {
                // Đảm bảo số ghế là chẵn cho ghế đôi
                if ($calculatedSeats % 2 !== 0) {
                    $calculatedSeats = $calculatedSeats - 1; // Làm tròn xuống số chẵn
                }
            }
            
            $requiredSeats[$seatTypeId] = $calculatedSeats;
        }

        // Đếm ghế hiện có theo loại
        $existingSeats = $room->seats()
            ->select('seat_type_id', DB::raw('count(*) as count'))
            ->groupBy('seat_type_id')
            ->pluck('count', 'seat_type_id')
            ->toArray();

        foreach ($requiredSeats as $seatTypeId => $required) {
            $existing = $existingSeats[$seatTypeId] ?? 0;
            $existingSeatsByType[$seatTypeId] = $existing;
        }

        $currentTypeSeats = $existingSeatsByType[$data['seat_type_id']] ?? 0;
        $requiredTypeSeats = $requiredSeats[$data['seat_type_id']] ?? 0;
        $remainingCapacity = $room->capacity - array_sum($existingSeats);
        $totalSeatsToAdd = min($requiredTypeSeats - $currentTypeSeats, $remainingCapacity);

        return [
            'required' => $requiredSeats,
            'existing' => $existingSeatsByType,
            'current_type_seats' => $currentTypeSeats,
            'required_type_seats' => $requiredTypeSeats,
            'remaining_capacity' => $remainingCapacity,
            'total_seats_to_add' => $totalSeatsToAdd
        ];
    }

    /**
     * Validate layout ghế (hàng, ghế/hàng)
     */
    private function validateSeatLayout(Room $room, array $seatCalculation, array $data): array
    {
        $warnings = [];
        $seatsPerRow = $data['seats_per_row'];
        $totalSeatsToAdd = $seatCalculation['total_seats_to_add'];
        $minSeatsPerRow = $data['min_seats_per_row'];
        $seatTypeId = $data['seat_type_id'];

        if ($seatsPerRow < $minSeatsPerRow) {
            throw new \InvalidArgumentException("Số ghế mỗi hàng phải ≥ {$minSeatsPerRow}.");
        }

        if ($totalSeatsToAdd <= 0) {
            throw new \InvalidArgumentException('Không có ghế nào cần thêm cho loại này.');
        }

        // Validation cho ghế đôi
        $seatType = \App\Models\SeatType::find($seatTypeId);
        if ($seatType && $this->isCoupleSeaType($seatType->name)) {
            // Kiểm tra số ghế mỗi hàng phải chẵn
            if ($seatsPerRow % 2 !== 0) {
                throw new \InvalidArgumentException("Loại ghế đôi '{$seatType->name}' phải có số ghế mỗi hàng là số chẵn. Hiện tại: {$seatsPerRow} ghế/hàng.");
            }
            
            // Kiểm tra tổng số ghế phải chẵn
            if ($totalSeatsToAdd % 2 !== 0) {
                throw new \InvalidArgumentException("Loại ghế đôi '{$seatType->name}' phải có tổng số ghế là số chẵn. Hiện tại: {$totalSeatsToAdd} ghế.");
            }
        }

        $rows = ceil($totalSeatsToAdd / $seatsPerRow);
        $totalSeatsProposed = $rows * $seatsPerRow;
        
        // Cảnh báo ghế dư
        if ($totalSeatsProposed > $totalSeatsToAdd) {
            $excessSeats = $totalSeatsProposed - $totalSeatsToAdd;
            $warnings[] = "Sẽ có {$excessSeats} ghế dư do không chia hết số hàng.";
        }

        // Cảnh báo vượt capacity
        if ($totalSeatsProposed > $seatCalculation['remaining_capacity']) {
            $warnings[] = "Số ghế muốn thêm vượt quá sức chứa còn lại.";
        }

        // Đề xuất layout tối ưu
        $optimalLayout = $this->suggestOptimalLayout($totalSeatsToAdd, $minSeatsPerRow);
        if ($optimalLayout && $optimalLayout !== ['rows' => $rows, 'seats_per_row' => $seatsPerRow]) {
            $warnings[] = "Đề xuất layout tối ưu: {$optimalLayout['rows']} hàng x {$optimalLayout['seats_per_row']} ghế/hàng.";
        }

        return [
            'valid' => true,
            'warnings' => $warnings,
            'rows' => $rows,
            'seats_per_row' => $seatsPerRow,
            'total_seats_proposed' => $totalSeatsProposed
        ];
    }

    /**
     * Đề xuất layout ghế tối ưu
     */
    private function suggestOptimalLayout(int $totalSeats, int $minSeatsPerRow): ?array
    {
        $maxSeatsPerRow = 50;
        $maxRows = 26;

        // Tìm layout chia hết và cân bằng nhất
        for ($seatsPerRow = $minSeatsPerRow; $seatsPerRow <= $maxSeatsPerRow; $seatsPerRow++) {
            if ($totalSeats % $seatsPerRow === 0) {
                $rows = $totalSeats / $seatsPerRow;
                if ($rows <= $maxRows) {
                    return ['rows' => $rows, 'seats_per_row' => $seatsPerRow];
                }
            }
        }

        return null;
    }

    /**
     * Lưu cấu hình tỷ lệ ghế
     */
    private function saveSeatConfiguration(Room $room, array $seatPercentages): void
    {
        // Xóa cấu hình cũ
        RoomSeatConfiguration::where('room_id', $room->id)->delete();

        // Tạo cấu hình mới
        foreach ($seatPercentages as $seatTypeId => $percentage) {
            if ($percentage > 0) {
                RoomSeatConfiguration::create([
                    'room_id' => $room->id,
                    'seat_type_id' => $seatTypeId,
                    'percentage' => $percentage
                ]);
            }
        }
    }

    /**
     * Tạo ghế thực tế
     */
    private function createSeats(Room $room, array $seatCalculation, array $data): int
    {
        $seatsPerRow = $data['seats_per_row'];
        $seatTypeId = $data['seat_type_id'];
        $totalSeatsToAdd = $seatCalculation['total_seats_to_add'];
        
        // Điều chỉnh seats per row cho ghế đôi
        $seatType = \App\Models\SeatType::find($seatTypeId);
        if ($seatType && $this->isCoupleSeaType($seatType->name)) {
            // Đảm bảo số ghế mỗi hàng là chẵn cho ghế đôi
            if ($seatsPerRow % 2 !== 0) {
                $seatsPerRow = $seatsPerRow - 1; // Làm tròn xuống số chẵn
            }
        }
        
        // Lấy existing seats để tránh duplicate
        $existingSeatsMap = $room->seats()
            ->get()
            ->groupBy('row_char')
            ->map->pluck('seat_number')
            ->toArray();

        $rows = ceil($totalSeatsToAdd / $seatsPerRow);
        $createdSeats = 0;
        
        // Xác định row char bắt đầu
        $maxRowChar = $room->seats()->max('row_char');
        $startRowIndex = $maxRowChar ? ord(strtoupper($maxRowChar)) - 64 : 0;

        for ($i = 0; $i < $rows && $createdSeats < $totalSeatsToAdd; $i++) {
            $rowChar = chr(65 + $startRowIndex + $i);
            
            if (strlen($rowChar) > 5) {
                throw new \Exception('Số hàng vượt quá giới hạn ký tự cho phép.');
            }

            for ($j = 1; $j <= $seatsPerRow && $createdSeats < $totalSeatsToAdd; $j++) {
                $seatNumber = str_pad($j, 2, '0', STR_PAD_LEFT);
                
                // Skip nếu ghế đã tồn tại
                if (isset($existingSeatsMap[$rowChar]) && 
                    in_array($seatNumber, $existingSeatsMap[$rowChar])) {
                    continue;
                }

                Seat::create([
                    'room_id' => $room->id,
                    'seat_type_id' => $seatTypeId,
                    'row_char' => $rowChar,
                    'seat_number' => $seatNumber,
                    'status' => SeatStatus::Available
                ]);

                $createdSeats++;
            }
        }

        return $createdSeats;
    }

    /**
     * Tính toán seat type tiếp theo cần thêm
     */
    private function calculateNextSeatType(Room $room, array $seatPercentages): ?array
    {
        $seatCalculation = $this->roomSeatValidation->calculateRequiredSeats($room, $seatPercentages);
        
        foreach ($seatCalculation['needed'] as $seatTypeId => $needed) {
            if ($needed > 0) {
                $seatType = SeatType::find($seatTypeId);
                return [
                    'id' => $seatTypeId,
                    'name' => $seatType->name,
                    'needed' => $needed
                ];
            }
        }

        return null;
    }

    /**
     * Tạo suggestions cho lần thêm ghế tiếp theo
     */
    private function generateSuggestions(Room $room, ?array $nextSeatType): array
    {
        if (!$nextSeatType) {
            return ['message' => 'Phòng đã hoàn thành cấu hình ghế.'];
        }

        $suggestions = [];
        $needed = $nextSeatType['needed'];
        
        // Đề xuất layout tối ưu
        $optimalLayout = $this->suggestOptimalLayout($needed, 1);
        if ($optimalLayout) {
            $suggestions[] = "Đề xuất cho {$nextSeatType['name']}: {$optimalLayout['rows']} hàng x {$optimalLayout['seats_per_row']} ghế/hàng";
        }

        // Đề xuất phân bổ đều
        $evenDistribution = $this->suggestEvenDistribution($needed);
        if ($evenDistribution) {
            $suggestions[] = "Hoặc phân bổ đều: {$evenDistribution}";
        }

        return $suggestions;
    }

    /**
     * Đề xuất phân bổ ghế đều
     */
    private function suggestEvenDistribution(int $totalSeats): ?string
    {
        $optimalSeatsPerRow = min(20, $totalSeats); // Giới hạn 20 ghế/hàng cho phòng chuẩn
        
        for ($seatsPerRow = $optimalSeatsPerRow; $seatsPerRow >= 5; $seatsPerRow--) {
            if ($totalSeats % $seatsPerRow === 0) {
                $rows = $totalSeats / $seatsPerRow;
                return "{$rows} hàng x {$seatsPerRow} ghế/hàng (chia hết)";
            }
        }

        return null;
    }

    /**
     * Kiểm tra xem loại ghế có phải là ghế đôi không
     */
    private function isCoupleSeaType(string $seatTypeName): bool
    {
        $typeName = strtolower($seatTypeName);
        return str_contains($typeName, 'couple') || 
               str_contains($typeName, 'sweetbox') || 
               str_contains($typeName, 'bed') ||
               str_contains($typeName, 'sofa');
    }
}
