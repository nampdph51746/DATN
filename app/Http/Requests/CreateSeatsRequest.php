<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Room;

class CreateSeatsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'min_seats_per_row' => 'required|integer|min:1|max:50',
            'seats_per_row' => 'required|integer|min:1|max:50|gte:min_seats_per_row',
            'ignore_constraints' => 'boolean',
            'seat_type_percentages' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Validate tổng tỷ lệ = 100%
                    $total = array_sum($value);
                    if (abs($total - 100) > 0.01) {
                        $fail('Tổng tỷ lệ ghế phải bằng 100%.');
                    }
                    
                    // Validate từng tỷ lệ
                    foreach ($value as $seatTypeId => $percentage) {
                        if (!is_numeric($percentage) || $percentage < 0 || $percentage > 100) {
                            $fail('Mỗi tỷ lệ ghế phải từ 0 đến 100.');
                        }
                    }
                }
            ],
            // Validate seat type có trong phòng
            'seat_type_id' => [
                'required',
                'exists:seat_types,id',
                function ($attribute, $value, $fail) {
                    $room = Room::find($this->room_id);
                    if ($room && method_exists($room, 'allowedSeatTypes')) {
                        $allowedTypes = $room->allowedSeatTypes()->pluck('id')->toArray();
                        if (!in_array($value, $allowedTypes)) {
                            $fail('Loại ghế không được phép trong phòng này.');
                        }
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'room_id.required' => 'Phòng chiếu là bắt buộc.',
            'room_id.exists' => 'Phòng chiếu không tồn tại.',
            'seat_type_id.required' => 'Loại ghế là bắt buộc.',
            'seat_type_id.exists' => 'Loại ghế không tồn tại.',
            'min_seats_per_row.required' => 'Số ghế tối thiểu mỗi hàng là bắt buộc.',
            'min_seats_per_row.integer' => 'Số ghế tối thiểu mỗi hàng phải là số nguyên.',
            'min_seats_per_row.min' => 'Số ghế tối thiểu mỗi hàng phải lớn hơn hoặc bằng 1.',
            'min_seats_per_row.max' => 'Số ghế tối thiểu mỗi hàng không được vượt quá 50.',
            'seats_per_row.required' => 'Số ghế mỗi hàng là bắt buộc.',
            'seats_per_row.integer' => 'Số ghế mỗi hàng phải là số nguyên.',
            'seats_per_row.min' => 'Số ghế mỗi hàng phải lớn hơn hoặc bằng 1.',
            'seats_per_row.max' => 'Số ghế mỗi hàng không được vượt quá 50.',
            'seats_per_row.gte' => 'Số ghế mỗi hàng phải lớn hơn hoặc bằng số ghế tối thiểu.',
            'seat_type_percentages.required' => 'Tỷ lệ loại ghế là bắt buộc.',
        ];
    }

    /**
     * Get validated data with additional processing
     */
    public function getValidatedData(): array
    {
        $data = $this->validated();
        
        // Ensure percentages are floats
        if (isset($data['seat_type_percentages'])) {
            $data['seat_type_percentages'] = array_map('floatval', $data['seat_type_percentages']);
        }
        
        return $data;
    }
}
