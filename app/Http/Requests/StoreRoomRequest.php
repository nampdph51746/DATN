<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

 // Thêm dòng này ở đầu file nếu chưa có

public function rules(): array
{
    return [
        'cinema_id' => 'required|integer',
        'room_type_id' => 'nullable|integer',
        'name' => [
            'required',
            'string',
            'max:100',
            Rule::unique('rooms', 'name')->where(function ($query) {
                return $query->where('cinema_id', $this->cinema_id);
            }),
        ],
        'status' => 'nullable|string|max:20',
    ];
}

    /**
     * Validate capacity for rooms with couple seat types
     */
    private function validateCapacityForCoupleSeats($attribute, $value, $fail)
    {
        $roomTypeId = $this->input('room_type_id');

        if (!$roomTypeId) {
            return; // Skip validation if no room type selected
        }

        try {
            // Lấy allowed seat types thông qua constraints table
            $constraints = \App\Models\RoomSeatTypeConstraint::where('room_type_id', $roomTypeId)
                ->with('seatType')
                ->get();

            if ($constraints->isEmpty()) {
                return; // Không có constraints thì skip validation
            }

            $coupleSeatsCount = 0;
            $hasCoupleSeats = false;
            $coupleSeatsNames = [];

            foreach ($constraints as $constraint) {
                if ($constraint->seatType && $this->isCoupleSeaType($constraint->seatType->name)) {
                    $hasCoupleSeats = true;
                    $coupleSeatsNames[] = $constraint->seatType->name;
                    $coupleSeatsCount++;
                }
            }

            // Nếu có ghế đôi, capacity phải chẵn
            if ($hasCoupleSeats && $value % 2 !== 0) {
                $coupleSeatsStr = implode(', ', $coupleSeatsNames);
                $suggestedCapacity = $value + 1; // Đề xuất số chẵn gần nhất

                $roomType = \App\Models\RoomType::find($roomTypeId);
                $roomTypeName = $roomType ? $roomType->name : 'Unknown';

                $fail("Phòng loại '{$roomTypeName}' có chứa ghế đôi ({$coupleSeatsStr}) nên sức chứa phải là số chẵn. Đề xuất: {$suggestedCapacity} ghế.");
            }

            // Đề xuất sức chứa phù hợp
            if ($hasCoupleSeats) {
                $roomType = \App\Models\RoomType::find($roomTypeId);
                if ($roomType) {
                    $suggestions = $this->getSuggestedCapacities($roomType, $coupleSeatsCount);
                    if (!empty($suggestions)) {
                        session()->flash('capacity_suggestions', $suggestions);
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error và vẫn fail validation để đảm bảo an toàn
            \Log::error('Error validating capacity for couple seats: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'capacity' => $value,
                'trace' => $e->getTraceAsString()
            ]);
            $fail('Có lỗi khi kiểm tra sức chứa cho ghế đôi. Vui lòng thử lại.');
        }
    }

    /**
     * Check if seat type is couple seat
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
     * Get suggested capacities for room with couple seats
     */
    private function getSuggestedCapacities($roomType, $coupleSeatsCount): array
    {
        $roomTypeName = strtolower($roomType->name);

        // Đề xuất dựa trên loại phòng
        $suggestions = [];

        if (str_contains($roomTypeName, 'vip') || str_contains($roomTypeName, 'premium')) {
            $suggestions = [40, 50, 60, 80]; // Phòng VIP ít ghế hơn
        } elseif (str_contains($roomTypeName, 'imax')) {
            $suggestions = [60, 80, 100, 120]; // IMAX lớn hơn
        } elseif (str_contains($roomTypeName, 'cine') && str_contains($roomTypeName, 'living')) {
            $suggestions = [30, 40, 50, 60]; // Cine & Living có mix ghế đơn và đôi
        } else {
            $suggestions = [50, 60, 80, 100]; // Standard room
        }

        return array_filter($suggestions, function ($capacity) {
            return $capacity % 2 === 0; // Chỉ trả về số chẵn
        });
    }

    /**
     * Tùy chỉnh thông báo lỗi validate.
     */
    public function messages(): array
    {
        return [
            'cinema_id.required' => 'Vui lòng chọn rạp chiếu.',
            'cinema_id.integer' => 'Rạp chiếu không hợp lệ.',
            'room_type_id.integer' => 'Loại phòng không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên phòng.',
            'name.string' => 'Tên phòng phải là chuỗi ký tự.',
            'name.max' => 'Tên phòng không được vượt quá 100 ký tự.',
            'capacity.required' => 'Vui lòng nhập sức chứa.',
            'capacity.integer' => 'Sức chứa phải là số nguyên.',
            'capacity.min' => 'Sức chứa phải lớn hơn 0.',
            'status.string' => 'Trạng thái phải là chuỗi ký tự.',
            'name.unique' => 'Tên phòng chiếu đã tồn tại. Vui lòng chọn tên khác.',
            'status.max' => 'Trạng thái không được vượt quá 20 ký tự.',
        ];
    }
}