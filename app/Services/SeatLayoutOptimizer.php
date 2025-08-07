<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;

class SeatLayoutOptimizer
{
    /**
     * Tối ưu hóa layout ghế cho phòng chiếu
     */
    public function optimizeLayout(Room $room, array $seatRequirements): array
    {
        $totalCapacity = $room->capacity;
        $roomType = $room->roomType->name ?? 'standard';
        
        return match($roomType) {
            'IMAX', '4DX' => $this->optimizeSpecialRoomLayout($seatRequirements, $totalCapacity),
            'VIP' => $this->optimizeVIPRoomLayout($seatRequirements, $totalCapacity),
            default => $this->optimizeStandardRoomLayout($seatRequirements, $totalCapacity)
        };
    }

    /**
     * Layout cho phòng tiêu chuẩn
     */
    private function optimizeStandardRoomLayout(array $seatRequirements, int $totalCapacity): array
    {
        $layout = [];
        $recommendedSeatsPerRow = $this->calculateOptimalSeatsPerRow($totalCapacity);
        
        foreach ($seatRequirements as $seatTypeId => $count) {
            if ($count <= 0) continue;
            
            $seatType = SeatType::find($seatTypeId);
            $layout[] = [
                'seat_type_id' => $seatTypeId,
                'seat_type_name' => $seatType->name,
                'count' => $count,
                'recommended_layout' => $this->calculateBestLayout($count, $recommendedSeatsPerRow),
                'position_suggestions' => $this->suggestSeatPositions($seatType->name, $count)
            ];
        }
        
        return $layout;
    }

    /**
     * Layout cho phòng VIP
     */
    private function optimizeVIPRoomLayout(array $seatRequirements, int $totalCapacity): array
    {
        // VIP rooms typically have fewer seats per row for comfort
        $recommendedSeatsPerRow = min(16, ceil($totalCapacity / 10));
        
        $layout = [];
        foreach ($seatRequirements as $seatTypeId => $count) {
            if ($count <= 0) continue;
            
            $seatType = SeatType::find($seatTypeId);
            $layout[] = [
                'seat_type_id' => $seatTypeId,
                'seat_type_name' => $seatType->name,
                'count' => $count,
                'recommended_layout' => $this->calculateBestLayout($count, $recommendedSeatsPerRow),
                'position_suggestions' => $this->suggestVIPSeatPositions($seatType->name, $count)
            ];
        }
        
        return $layout;
    }

    /**
     * Layout cho phòng đặc biệt (IMAX, 4DX)
     */
    private function optimizeSpecialRoomLayout(array $seatRequirements, int $totalCapacity): array
    {
        // Special rooms may have specific seating arrangements
        $recommendedSeatsPerRow = min(20, ceil($totalCapacity / 8));
        
        $layout = [];
        foreach ($seatRequirements as $seatTypeId => $count) {
            if ($count <= 0) continue;
            
            $seatType = SeatType::find($seatTypeId);
            $layout[] = [
                'seat_type_id' => $seatTypeId,
                'seat_type_name' => $seatType->name,
                'count' => $count,
                'recommended_layout' => $this->calculateBestLayout($count, $recommendedSeatsPerRow),
                'position_suggestions' => $this->suggestSpecialSeatPositions($seatType->name, $count)
            ];
        }
        
        return $layout;
    }

    /**
     * Tính toán số ghế tối ưu mỗi hàng
     */
    private function calculateOptimalSeatsPerRow(int $totalCapacity): int
    {
        // Công thức tối ưu dựa trên capacity
        if ($totalCapacity <= 100) return 12;
        if ($totalCapacity <= 200) return 16;
        if ($totalCapacity <= 300) return 20;
        return 24;
    }

    /**
     * Tính layout tốt nhất cho số ghế cho trước
     */
    private function calculateBestLayout(int $seatCount, int $preferredSeatsPerRow): array
    {
        $bestLayouts = [];
        
        // Tìm các layout có thể (ưu tiên layout chia hết)
        for ($seatsPerRow = max(1, $preferredSeatsPerRow - 4); $seatsPerRow <= $preferredSeatsPerRow + 4; $seatsPerRow++) {
            $rows = ceil($seatCount / $seatsPerRow);
            $wastedSeats = ($rows * $seatsPerRow) - $seatCount;
            
            if ($rows <= 26) { // Max 26 rows (A-Z)
                $bestLayouts[] = [
                    'rows' => $rows,
                    'seats_per_row' => $seatsPerRow,
                    'wasted_seats' => $wastedSeats,
                    'efficiency' => $seatCount / ($rows * $seatsPerRow) * 100,
                    'is_exact_fit' => $wastedSeats === 0
                ];
            }
        }
        
        // Sắp xếp theo độ ưu tiên: exact fit > efficiency > ít waste
        usort($bestLayouts, function($a, $b) {
            if ($a['is_exact_fit'] && !$b['is_exact_fit']) return -1;
            if (!$a['is_exact_fit'] && $b['is_exact_fit']) return 1;
            if ($a['efficiency'] !== $b['efficiency']) return $b['efficiency'] <=> $a['efficiency'];
            return $a['wasted_seats'] <=> $b['wasted_seats'];
        });
        
        return $bestLayouts[0] ?? [
            'rows' => ceil($seatCount / $preferredSeatsPerRow),
            'seats_per_row' => $preferredSeatsPerRow,
            'wasted_seats' => 0,
            'efficiency' => 100,
            'is_exact_fit' => false
        ];
    }

    /**
     * Đề xuất vị trí ghế cho phòng tiêu chuẩn
     */
    private function suggestSeatPositions(string $seatTypeName, int $count): array
    {
        return match(strtolower($seatTypeName)) {
            'vip' => [
                'position' => 'center_back',
                'description' => 'Đặt ở giữa và phía sau để có tầm nhìn tốt nhất',
                'priority_rows' => ['D', 'E', 'F', 'G'],
                'avoid_rows' => ['A', 'B']
            ],
            'sweetbox' => [
                'position' => 'back_corners',
                'description' => 'Đặt ở góc phía sau để tạo không gian riêng tư',
                'priority_rows' => ['F', 'G', 'H'],
                'avoid_rows' => ['A', 'B', 'C']
            ],
            'regular' => [
                'position' => 'front_center',
                'description' => 'Lấp đầy các vị trí còn lại',
                'priority_rows' => ['A', 'B', 'C', 'D'],
                'avoid_rows' => []
            ],
            default => [
                'position' => 'any',
                'description' => 'Có thể đặt ở bất kỳ vị trí nào',
                'priority_rows' => [],
                'avoid_rows' => []
            ]
        };
    }

    /**
     * Đề xuất vị trí ghế cho phòng VIP
     */
    private function suggestVIPSeatPositions(string $seatTypeName, int $count): array
    {
        return match(strtolower($seatTypeName)) {
            'vip' => [
                'position' => 'throughout',
                'description' => 'Phân bổ đều trong phòng VIP',
                'priority_rows' => ['C', 'D', 'E'],
                'spacing' => 'wide'
            ],
            'premium' => [
                'position' => 'sweet_spot',
                'description' => 'Tập trung ở vị trí tầm nhìn tốt nhất',
                'priority_rows' => ['D', 'E'],
                'spacing' => 'extra_wide'
            ],
            default => [
                'position' => 'standard',
                'description' => 'Vị trí tiêu chuẩn',
                'priority_rows' => ['A', 'B', 'C'],
                'spacing' => 'normal'
            ]
        };
    }

    /**
     * Đề xuất vị trí ghế cho phòng đặc biệt
     */
    private function suggestSpecialSeatPositions(string $seatTypeName, int $count): array
    {
        return [
            'position' => 'optimized',
            'description' => "Tối ưu cho trải nghiệm {$seatTypeName}",
            'priority_rows' => ['C', 'D', 'E', 'F'],
            'special_notes' => [
                'Cân nhắc góc nhìn màn hình',
                'Tối ưu cho hệ thống âm thanh',
                'Đảm bảo an toàn cho thiết bị đặc biệt'
            ]
        ];
    }

    /**
     * Validate layout có khả thi không
     */
    public function validateLayout(array $layout, Room $room): array
    {
        $errors = [];
        $warnings = [];
        
        $totalSeats = array_sum(array_column($layout, 'count'));
        if ($totalSeats > $room->capacity) {
            $errors[] = "Tổng số ghế ({$totalSeats}) vượt quá sức chứa phòng ({$room->capacity}).";
        }
        
        $totalRows = array_sum(array_column($layout, 'rows'));
        if ($totalRows > 26) {
            $errors[] = "Tổng số hàng ({$totalRows}) vượt quá giới hạn (26 hàng).";
        }
        
        // Kiểm tra layout có hợp lý không
        foreach ($layout as $item) {
            if ($item['seats_per_row'] > 50) {
                $warnings[] = "Ghế {$item['seat_type_name']}: {$item['seats_per_row']} ghế/hàng có thể quá nhiều.";
            }
            
            if ($item['wasted_seats'] > 0) {
                $warnings[] = "Ghế {$item['seat_type_name']}: sẽ dư {$item['wasted_seats']} vị trí.";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Get layout suggestions for the frontend
     */
    public function getLayoutSuggestions(Room $room): array
    {
        $roomType = strtolower($room->roomType->name ?? 'standard');
        $capacity = $room->capacity;
        $allowedSeatTypes = $room->allowedSeatTypes();
        
        // Get current seat type percentages if set
        $currentPercentages = \App\Models\RoomSeatConfiguration::where('room_id', $room->id)
            ->pluck('percentage', 'seat_type_id')
            ->toArray();
        
        $suggestions = [
            'room_info' => [
                'name' => $room->name,
                'type' => $room->roomType->name ?? 'Standard',
                'capacity' => $capacity,
                'allowed_seat_types' => $allowedSeatTypes->pluck('name')->toArray()
            ],
            'recommended_percentages' => $this->getRecommendedPercentages($roomType, $allowedSeatTypes, $room),
            'optimal_layout' => $this->calculateOptimalLayout($room, $allowedSeatTypes),
            'individual_seat_layouts' => $this->calculateIndividualSeatLayouts($room, $allowedSeatTypes),
            'layout_tips' => $this->getLayoutTips($roomType, $capacity),
            'has_configuration' => !empty($currentPercentages)
        ];
        
        return $suggestions;
    }

    /**
     * Get recommended percentages based on room type and allowed seat types
     */
    private function getRecommendedPercentages(string $roomType, $allowedSeatTypes, Room $room = null): array
    {
        if ($allowedSeatTypes->isEmpty()) {
            return [];
        }

        $result = [];
        
        // Lấy constraints từ database cho room type này
        if ($room && $room->roomType) {
            $constraints = $room->roomType->seatTypeConstraints;
            
            if ($constraints && $constraints->isNotEmpty()) {
                // Sử dụng constraints từ database
                foreach ($allowedSeatTypes as $seatType) {
                    $constraint = $constraints->where('seat_type_id', $seatType->id)->first();
                    if ($constraint) {
                        // Sử dụng giá trị trung bình giữa min và max, hoặc min nếu không có max
                        if ($constraint->max_percentage !== null) {
                            $result[$seatType->id] = round(($constraint->min_percentage + $constraint->max_percentage) / 2, 2);
                        } else {
                            $result[$seatType->id] = $constraint->min_percentage;
                        }
                    }
                }
                
                // Điều chỉnh để tổng = 100%
                $total = array_sum($result);
                if ($total > 0 && $total !== 100) {
                    foreach ($result as $id => $percentage) {
                        $result[$id] = round(($percentage / $total) * 100, 2);
                    }
                }
                
                return $result;
            }
        }

        // Fallback: Sử dụng rules cũ nếu không có constraints
        $seatTypeNames = $allowedSeatTypes->pluck('name', 'id')->map(function($name) {
            return strtolower($name);
        })->toArray();
        
        // Create a sorted string key from seat type names for matching
        $sortedNames = array_values($seatTypeNames);
        sort($sortedNames);
        $seatTypesKey = implode(',', $sortedNames);
        
        // Define percentage rules by room type and seat combinations  
        $percentageRules = [
            'premium' => [
                'imax premium,imax standard' => ['imax standard' => 70, 'imax premium' => 30],
                'couple bed,couple sofa' => ['couple sofa' => 60, 'couple bed' => 40],
                'couple sofa,vip' => ['vip' => 70, 'couple sofa' => 30],
                'couple bed,vip' => ['vip' => 65, 'couple bed' => 35],
                'regular,vip' => ['vip' => 70, 'regular' => 30],
                // Single types
                'vip' => ['vip' => 100],
                'couple sofa' => ['couple sofa' => 100],
                'couple bed' => ['couple bed' => 100],
                'regular' => ['regular' => 100],
                'imax standard' => ['imax standard' => 100],
                'imax premium' => ['imax premium' => 100]
            ],
            'imax' => [
                'imax premium,imax standard' => ['imax standard' => 70, 'imax premium' => 30],
                'couple bed,couple sofa' => ['couple sofa' => 60, 'couple bed' => 40],
                'couple sofa,vip' => ['vip' => 70, 'couple sofa' => 30],
                'couple bed,vip' => ['vip' => 65, 'couple bed' => 35],
                'regular,vip' => ['vip' => 70, 'regular' => 30],
                // Single types
                'vip' => ['vip' => 100],
                'couple sofa' => ['couple sofa' => 100],
                'couple bed' => ['couple bed' => 100],
                'regular' => ['regular' => 100],
                'imax standard' => ['imax standard' => 100],
                'imax premium' => ['imax premium' => 100]
            ],
            'vip' => [
                'couple sofa,vip' => ['vip' => 60, 'couple sofa' => 40],
                'couple bed,vip' => ['vip' => 70, 'couple bed' => 30],
                'regular,vip' => ['vip' => 70, 'regular' => 30],
                // Single types
                'vip' => ['vip' => 100],
                'couple sofa' => ['couple sofa' => 100],
                'couple bed' => ['couple bed' => 100],
                'regular' => ['regular' => 100]
            ],
            'standard' => [
                'couple sofa,regular,vip' => ['regular' => 60, 'vip' => 25, 'couple sofa' => 15],
                'regular,vip' => ['regular' => 70, 'vip' => 30],
                'couple sofa,regular' => ['regular' => 75, 'couple sofa' => 25],
                // Single types
                'regular' => ['regular' => 100],
                'vip' => ['vip' => 100],
                'couple sofa' => ['couple sofa' => 100]
            ]
        ];
        
        $rules = $percentageRules[strtolower($roomType)] ?? $percentageRules['standard'];
        
        // Check if we have exact match
        if (isset($rules[$seatTypesKey])) {
            $percentages = $rules[$seatTypesKey];
            // Map percentages back to seat type IDs
            $result = [];
            foreach ($seatTypeNames as $id => $name) {
                $result[$id] = $percentages[$name] ?? 0;
            }
            return $result;
        }
        
        // If no exact match, distribute evenly
        $evenPercentage = 100 / count($seatTypeNames);
        $result = [];
        foreach ($seatTypeNames as $id => $name) {
            $result[$id] = round($evenPercentage, 0);
        }
        
        // Adjust last item to make total = 100
        $total = array_sum($result);
        if ($total !== 100) {
            $lastKey = array_key_last($result);
            $result[$lastKey] += (100 - $total);
        }
        
        return $result;
    }

    /**
     * Calculate individual layout suggestions for each seat type
     */
    private function calculateIndividualSeatLayouts(Room $room, $allowedSeatTypes): array
    {
        $capacity = $room->capacity;
        $roomType = strtolower($room->roomType->name ?? 'standard');
        $recommendedPercentages = $this->getRecommendedPercentages($roomType, $allowedSeatTypes, $room);
        
        $individualLayouts = [];
        
        foreach ($allowedSeatTypes as $seatType) {
            $percentage = $recommendedPercentages[$seatType->id] ?? 0;
            if ($percentage <= 0) continue;
            
            $seatCount = round(($percentage / 100) * $capacity);
            if ($seatCount <= 0) continue;
            
            // Kiểm tra ghế đôi - không tự động điều chỉnh, chỉ cảnh báo
            $isCoupleSeaType = $this->isCoupleSeaType($seatType->name);
            $adjustmentNote = null;
            
            if ($isCoupleSeaType && $seatCount % 2 !== 0) {
                $adjustmentNote = "⚠️ Số ghế lẻ ({$seatCount}) không phù hợp cho ghế đôi. Cần điều chỉnh tỷ lệ để có số ghế chẵn.";
            }
            
            // Tính layout tối ưu cho loại ghế này
            $smartLayout = $this->calculateSmartLayoutForSeatType($seatCount, $roomType, $seatType->name);
            
            $individualLayouts[] = [
                'seat_type_id' => $seatType->id,
                'seat_type_name' => $seatType->name,
                'seat_count' => $seatCount,
                'percentage' => $percentage,
                'optimal_rows' => $smartLayout['rows'],
                'seats_per_row' => $smartLayout['seats_per_row_optimal'],
                'efficiency' => $smartLayout['efficiency'],
                'wasted_seats' => $smartLayout['wasted_seats'],
                'layout_suggestion' => $this->getSeatTypeLayoutSuggestion($seatType->name, $smartLayout),
                'position_recommendation' => $this->getSeatTypePositionRecommendation($seatType->name, $roomType),
                'adjustment_note' => $adjustmentNote
            ];
        }
        
        return $individualLayouts;
    }

    /**
     * Check if seat type is couple seat (requires even number per row)
     */
    private function isCoupleSeaType(string $seatTypeName): bool
    {
        $typeName = strtolower($seatTypeName);
        return str_contains($typeName, 'couple') || 
               str_contains($typeName, 'sweetbox') || 
               str_contains($typeName, 'bed') ||
               str_contains($typeName, 'sofa');
    }

    /**
     * Calculate smart layout for specific seat type
     */
    private function calculateSmartLayoutForSeatType(int $seatCount, string $roomType, string $seatTypeName): array
    {
        // Điều chỉnh range dựa trên loại ghế
        $seatRanges = $this->getSeatsPerRowRangeForSeatType($seatTypeName, $roomType);
        $isCoupleSeaType = $this->isCoupleSeaType($seatTypeName);
        
        $possibleLayouts = [];
        
        for ($seatsPerRow = $seatRanges['min']; $seatsPerRow <= $seatRanges['max']; $seatsPerRow++) {
            // Nếu là ghế đôi, chỉ xét số ghế mỗi hàng chẵn
            if ($isCoupleSeaType && $seatsPerRow % 2 !== 0) {
                continue; // Bỏ qua số lẻ cho ghế đôi
            }
            
            $rows = ceil($seatCount / $seatsPerRow);
            
            if ($rows > 0 && $rows <= 26) { 
                // Tính layout thực tế
                $fullRows = floor($seatCount / $seatsPerRow);
                $lastRowSeats = $seatCount % $seatsPerRow;
                
                // Nếu chia hết thì tất cả hàng đều đầy
                if ($lastRowSeats === 0) {
                    $lastRowSeats = $seatsPerRow;
                }
                $actualSeatCount = $seatCount;
                
                $totalSeatsInGrid = $rows * $seatsPerRow;
                $wastedSeats = $totalSeatsInGrid - $actualSeatCount;
                $efficiency = ($actualSeatCount / $totalSeatsInGrid) * 100;
                $isExactFit = $wastedSeats === 0;
                
                $score = $efficiency;
                if ($isExactFit) $score += 20;
                if ($wastedSeats <= 2) $score += 10;
                if ($wastedSeats > 5) $score -= 15;
                
                // Bonus cho ghế đôi với layout đúng (số chẵn)
                if ($isCoupleSeaType && $seatsPerRow % 2 === 0 && $lastRowSeats % 2 === 0) {
                    $score += 15;
                }
                // Penalty cho ghế đôi với số lẻ
                if ($isCoupleSeaType && ($seatsPerRow % 2 !== 0 || $lastRowSeats % 2 !== 0)) {
                    $score -= 30;
                }
                
                $possibleLayouts[] = [
                    'rows' => $rows,
                    'seats_per_row' => $seatsPerRow,
                    'full_rows' => $fullRows,
                    'last_row_seats' => $lastRowSeats,
                    'total_seats' => $totalSeatsInGrid,
                    'actual_seats' => $actualSeatCount,
                    'wasted_seats' => $wastedSeats,
                    'efficiency' => round($efficiency, 1),
                    'is_exact_fit' => $isExactFit,
                    'score' => $score,
                    'is_couple_compatible' => $isCoupleSeaType ? ($seatsPerRow % 2 === 0 && $lastRowSeats % 2 === 0) : true
                ];
            }
        }
        
        usort($possibleLayouts, fn($a, $b) => $b['score'] <=> $a['score']);
        
        $bestLayout = $possibleLayouts[0] ?? [
            'rows' => ceil($seatCount / 8),
            'seats_per_row' => 8,
            'full_rows' => floor($seatCount / 8),
            'last_row_seats' => $seatCount % 8 ?: 8,
            'efficiency' => 100,
            'wasted_seats' => 0,
            'actual_seats' => $seatCount
        ];
        
        return [
            'rows' => $bestLayout['rows'],
            'seats_per_row_optimal' => $bestLayout['seats_per_row'],
            'full_rows' => $bestLayout['full_rows'],
            'last_row_seats' => $bestLayout['last_row_seats'],
            'efficiency' => $bestLayout['efficiency'],
            'wasted_seats' => $bestLayout['wasted_seats'],
            'actual_seats' => $bestLayout['actual_seats'] ?? $seatCount
        ];
    }

    /**
     * Get seats per row range for specific seat type
     */
    private function getSeatsPerRowRangeForSeatType(string $seatTypeName, string $roomType): array
    {
        $typeName = strtolower($seatTypeName);
        
        // Couple seats - ưu tiên layout 3-4 hàng thay vì nhiều hàng ít ghế
        if (str_contains($typeName, 'couple sofa')) {
            // Cho 30 ghế Couple Sofa: 3-4 hàng x 7-10 ghế/hàng
            return ['min' => 6, 'max' => 12];
        }
        
        if (str_contains($typeName, 'couple bed')) {
            // Cho 20 ghế Couple Bed: 2-3 hàng x 7-10 ghế/hàng
            return ['min' => 6, 'max' => 12];
        }
        
        if (str_contains($typeName, 'couple')) {
            // Generic couple seats
            return ['min' => 6, 'max' => 10];
        }
        
        // VIP/Premium seats cần không gian hơn
        if (str_contains($typeName, 'vip') || str_contains($typeName, 'premium')) {
            return ['min' => 6, 'max' => 12];
        }
        
        // Regular seats có thể xếp chặt hơn
        if (str_contains($typeName, 'regular') || str_contains($typeName, 'standard')) {
            return ['min' => 8, 'max' => 16];
        }
        
        // Default cho IMAX, v.v.
        return $this->getSeatsPerRowRange($roomType);
    }

    /**
     * Get layout suggestion text for seat type
     */
    private function getSeatTypeLayoutSuggestion(string $seatTypeName, array $layout): string
    {
        $rows = $layout['rows'];
        $seatsPerRow = $layout['seats_per_row_optimal'];
        $fullRows = $layout['full_rows'] ?? 0;
        $lastRowSeats = $layout['last_row_seats'] ?? $seatsPerRow;
        $efficiency = $layout['efficiency'];
        $waste = $layout['wasted_seats'];
        $actualSeats = $layout['actual_seats'] ?? 0;
        $isCoupleSeaType = $this->isCoupleSeaType($seatTypeName);
        
        if ($waste == 0) {
            $suggestion = "{$rows} hàng x {$seatsPerRow} ghế/hàng (Perfect fit! ✓)";
        } else {
            if ($fullRows > 0 && $lastRowSeats !== $seatsPerRow) {
                $suggestion = "{$fullRows} hàng x {$seatsPerRow} ghế + 1 hàng x {$lastRowSeats} ghế";
            } else {
                $suggestion = "{$rows} hàng x {$seatsPerRow} ghế/hàng";
            }
            
            if ($waste <= 2) {
                $suggestion .= " (Dư {$waste} vị trí)";
            } else {
                $suggestion .= " (⚠️ Dư {$waste} vị trí)";
            }
        }
        
        // Thêm thông tin cho ghế đôi
        if ($isCoupleSeaType) {
            $coupleCount = floor($actualSeats / 2);
            $remainingSeats = $actualSeats % 2;
            
            if ($remainingSeats === 0) {
                $suggestion .= " → {$coupleCount} cặp ghế đôi ✓";
            } else {
                $suggestion .= " → {$coupleCount} cặp + {$remainingSeats} ghế lẻ ❌";
                $suggestion .= "\n⚠️ CẢNH BÁO: Ghế đôi không thể có số lẻ! Cần điều chỉnh tỷ lệ.";
            }
            
            // Kiểm tra số ghế mỗi hàng
            if ($seatsPerRow % 2 !== 0 || $lastRowSeats % 2 !== 0) {
                $suggestion .= "\n⚠️ CẢNH BÁO: Số ghế mỗi hàng phải chẵn cho ghế đôi!";
            }
        }
        
        return $suggestion;
    }

    /**
     * Get position recommendation for seat type
     */
    private function getSeatTypePositionRecommendation(string $seatTypeName, string $roomType): string
    {
        $typeName = strtolower($seatTypeName);
        $roomTypeLower = strtolower($roomType);
        
        if (str_contains($typeName, 'couple sofa')) {
            if ($roomTypeLower === 'imax') {
                return "Hàng trước (A-B): Góc nhìn tốt cho màn hình lớn";
            }
            return "Hàng trước-giữa (A, B, C): Tầm nhìn tối ưu, dễ ra vào";
        }
        
        if (str_contains($typeName, 'couple bed')) {
            if ($roomTypeLower === 'imax') {
                return "Hàng sau (E-F): Privacy cao, không gian rộng cho ghế nằm";
            }
            return "Hàng sau cùng (G, H): Tối đa privacy và không gian";
        }
        
        if (str_contains($typeName, 'couple')) {
            return "Hàng sau (F, G, H): Riêng tư cho cặp đôi";
        }
        
        if (str_contains($typeName, 'vip') || str_contains($typeName, 'premium')) {
            return "Sweet spot (D, E, F): Vị trí tầm nhìn và âm thanh tốt nhất";
        }
        
        if (str_contains($typeName, 'regular') || str_contains($typeName, 'standard')) {
            return "Hàng đầu-giữa (A-D): Tầm nhìn tốt, giá cả hợp lý";
        }
        
        return "Phân bổ theo thiết kế phòng chiếu";
    }

    /**
     * Calculate optimal layout based on room and seat types
     */
    private function calculateOptimalLayout(Room $room, $allowedSeatTypes): array
    {
        $capacity = $room->capacity;
        $roomType = strtolower($room->roomType->name ?? 'standard');
        
        // Seat spacing by type (wider seats need more space)
        $seatSpacing = [];
        foreach ($allowedSeatTypes as $seatType) {
            $typeName = strtolower($seatType->name);
            if (str_contains($typeName, 'couple')) {
                $seatSpacing[$seatType->id] = 2; // Couple seats are wider
            } elseif (str_contains($typeName, 'vip')) {
                $seatSpacing[$seatType->id] = 1.5; // VIP seats are slightly wider
            } else {
                $seatSpacing[$seatType->id] = 1; // Regular seats
            }
        }
        
        // Tính toán layout thông minh hơn
        $smartLayout = $this->calculateSmartLayout($capacity, $roomType);
        
        return [
            'optimal_rows' => $smartLayout['rows'],
            'seats_per_row_range' => [
                'min' => $smartLayout['seats_per_row_min'],
                'max' => $smartLayout['seats_per_row_max'],
                'optimal' => $smartLayout['seats_per_row_optimal']
            ],
            'seat_spacing' => $seatSpacing,
            'total_capacity' => $capacity,
            'efficiency' => $smartLayout['efficiency'] ?? 100,
            'wasted_seats' => $smartLayout['wasted_seats'] ?? 0
        ];
    }

    /**
     * Calculate smart layout based on capacity and room type
     */
    private function calculateSmartLayout(int $capacity, string $roomType): array
    {
        // Tìm layout tối ưu nhất cho capacity cụ thể
        $possibleLayouts = [];
        
        // Xác định range seats per row dựa trên room type
        $seatRanges = $this->getSeatsPerRowRange($roomType);
        
        // Thử các combination khác nhau
        for ($seatsPerRow = $seatRanges['min']; $seatsPerRow <= $seatRanges['max']; $seatsPerRow++) {
            $rows = ceil($capacity / $seatsPerRow);
            
            // Kiểm tra constraints hợp lý
            if ($rows > 0 && $rows <= 26) { // Max 26 rows (A-Z)
                $totalSeats = $rows * $seatsPerRow;
                $wastedSeats = $totalSeats - $capacity;
                $efficiency = ($capacity / $totalSeats) * 100;
                $isExactFit = $wastedSeats === 0;
                
                // Score layout - ưu tiên exact fit và high efficiency
                $score = $efficiency;
                if ($isExactFit) $score += 20; // Bonus cho exact fit
                if ($wastedSeats <= 2) $score += 10; // Bonus cho ít waste
                if ($wastedSeats > 10) $score -= 20; // Penalty cho waste nhiều
                
                $possibleLayouts[] = [
                    'rows' => $rows,
                    'seats_per_row' => $seatsPerRow,
                    'total_seats' => $totalSeats,
                    'wasted_seats' => $wastedSeats,
                    'efficiency' => round($efficiency, 1),
                    'is_exact_fit' => $isExactFit,
                    'score' => $score
                ];
            }
        }
        
        // Sắp xếp theo score cao nhất
        usort($possibleLayouts, fn($a, $b) => $b['score'] <=> $a['score']);
        
        $bestLayout = $possibleLayouts[0] ?? [
            'rows' => ceil($capacity / 10),
            'seats_per_row' => 10,
            'efficiency' => 100,
            'wasted_seats' => 0
        ];
        
        return [
            'rows' => $bestLayout['rows'],
            'seats_per_row_optimal' => $bestLayout['seats_per_row'],
            'seats_per_row_min' => max($seatRanges['min'], $bestLayout['seats_per_row'] - 2),
            'seats_per_row_max' => min($seatRanges['max'], $bestLayout['seats_per_row'] + 2),
            'efficiency' => $bestLayout['efficiency'],
            'wasted_seats' => $bestLayout['wasted_seats']
        ];
    }

    /**
     * Get optimal seats per row by room type
     */
    private function getOptimalSeatsPerRow(string $roomType, int $capacity): int
    {
        $baseSeatsPerRow = [
            'imax' => 14,      // IMAX rooms are usually wider
            'vip' => 8,        // VIP rooms have wider seats
            'standard' => 12,  // Standard configuration
            '4dx' => 6         // 4DX seats are much wider
        ];
        
        $base = $baseSeatsPerRow[$roomType] ?? 12;
        
        // Adjust based on capacity
        if ($capacity <= 50) {
            return max(4, $base - 4);
        } elseif ($capacity <= 100) {
            return $base;
        } elseif ($capacity <= 200) {
            return $base + 2;
        } else {
            return $base + 4;
        }
    }

    /**
     * Get recommended layout by room type
     */
    private function getRecommendedLayoutByType(string $roomType): array
    {
        $layouts = [
            'standard' => [
                'description' => 'Bố trí chuẩn cho phòng thường',
                'front_rows' => 'Ghế thường (3-4 hàng đầu)',
                'middle_rows' => 'Ghế VIP (hàng giữa, tầm nhìn tốt nhất)', 
                'back_rows' => 'Ghế couple (2-3 hàng cuối)',
                'aisles' => 'Lối đi giữa mỗi 6-8 ghế'
            ],
            'vip' => [
                'description' => 'Bố trí cao cấp cho phòng VIP',
                'front_rows' => 'Ghế thường (2-3 hàng đầu)',
                'middle_rows' => 'Ghế VIP (chiếm đa số)',
                'back_rows' => 'Ghế couple cao cấp',
                'aisles' => 'Lối đi rộng, mỗi 4-6 ghế'
            ],
            'imax' => [
                'description' => 'Bố trí đặc biệt cho IMAX',
                'front_rows' => 'Tránh hàng quá gần màn hình',
                'middle_rows' => 'Ghế VIP với góc nhìn tối ưu',
                'back_rows' => 'Ghế couple với khoảng cách lớn',
                'aisles' => 'Lối thoát hiểm rộng rãi'
            ]
        ];
        
        return $layouts[$roomType] ?? $layouts['standard'];
    }

    /**
     * Calculate optimal number of rows
     */
    private function calculateOptimalRows(Room $room): array
    {
        $capacity = $room->capacity;
        $roomType = strtolower($room->roomType->name ?? 'standard');
        
        // Optimal seats per row by room type
        $optimalSeatsPerRow = [
            'standard' => 12,
            'vip' => 10,
            'imax' => 16,
            '4dx' => 8
        ];
        
        $seatsPerRow = $optimalSeatsPerRow[$roomType] ?? 12;
        $optimalRows = max(8, min(20, ceil($capacity / $seatsPerRow)));
        
        return [
            'optimal' => $optimalRows,
            'min' => max(6, $optimalRows - 3),
            'max' => min(24, $optimalRows + 4),
            'seats_per_row' => $seatsPerRow
        ];
    }

    /**
     * Get seats per row range by room type
     */
    private function getSeatsPerRowRange(string $roomType): array
    {
        $ranges = [
            'standard' => ['min' => 8, 'max' => 16, 'optimal' => 12],
            'vip' => ['min' => 6, 'max' => 12, 'optimal' => 8],
            'imax' => ['min' => 8, 'max' => 18, 'optimal' => 12], // Điều chỉnh cho IMAX
            'premium' => ['min' => 6, 'max' => 14, 'optimal' => 10], // Thêm cho Premium
            '4dx' => ['min' => 4, 'max' => 10, 'optimal' => 6]
        ];
        
        // Nếu tên room type chứa keyword thì dùng config tương ứng
        foreach ($ranges as $type => $range) {
            if (str_contains(strtolower($roomType), $type)) {
                return $range;
            }
        }
        
        return $ranges['standard'];
    }

    /**
     * Get accessibility requirements
     */
    private function getAccessibilityRequirements(string $roomType): array
    {
        return [
            'wheelchair_spaces' => 'Tối thiểu 2% tổng ghế (tối thiểu 1 chỗ)',
            'companion_seats' => 'Ghế đi kèm cho người khuyết tật',
            'accessible_aisles' => 'Lối đi rộng tối thiểu 1.2m',
            'emergency_exits' => 'Lối thoát hiểm dễ tiếp cận'
        ];
    }

    /**
     * Get layout tips by room type
     */
    private function getLayoutTips(string $roomType, int $capacity = 100): array
    {
        $capacityTips = [];
        if ($capacity <= 50) {
            $capacityTips[] = "Phòng nhỏ: Tối ưu hóa không gian với 6-8 hàng";
        } elseif ($capacity <= 100) {
            $capacityTips[] = "Phòng trung bình: Cân bằng số hàng và ghế/hàng";
        } else {
            $capacityTips[] = "Phòng lớn: Cần nhiều lối thoát hiểm và accessibility";
        }

        $tips = [
            'imax' => [
                'Tránh hàng đầu do màn hình IMAX rất lớn',
                'Vị trí tốt nhất: 40-60% chiều dài phòng từ màn hình',
                'Ghế couple cần khoảng cách lớn hơn (120-140cm giữa hàng)',
                'Sound sweet spots: Trung tâm phòng cho âm thanh tối ưu'
            ],
            'vip' => [
                'Ghế VIP/Couple cần service aisle để phục vụ',
                'Khoảng cách giữa hàng: 110-130cm cho thoải mái',
                'Bố trí đèn LED dưới ghế cho di chuyển trong tối',
                'Privacy screens giữa các ghế couple'
            ],
            'standard' => [
                'Đặt ghế VIP ở vị trí tầm nhìn tốt nhất',
                'Ghế couple nên ở cuối phòng để riêng tư',
                'Để lối đi giữa mỗi 6-8 ghế',
                'Khoảng cách hàng ghế: 90-100cm'
            ]
        ];
        
        $roomTips = $tips[$roomType] ?? $tips['standard'];
        return array_merge($capacityTips, $roomTips);
    }
}
