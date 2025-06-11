<?php

namespace App\Http\Requests;

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
    public function rules(): array
    {
        return [
             'cinema_id' => 'required|integer',
            'room_type_id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'status' => 'nullable|string|max:20',
        ];
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
            'status.string' => 'Trạng thái phải là chuỗi ký tự.',
            'status.max' => 'Trạng thái không được vượt quá 20 ký tự.',
        ];
    }    
}
