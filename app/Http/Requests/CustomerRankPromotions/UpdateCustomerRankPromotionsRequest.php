<?php

namespace App\Http\Requests\CustomerRankPromotions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRankPromotionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:500',
            'discount_code' => 'nullable|string|max:50',

        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'description.string' => 'Mô tả phải là một chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'discount_code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'discount_code.string' => 'Mã giảm giá phải là một chuỗi ký tự.',
            'discount_code.required' => 'Mã giảm giá là bắt buộc nếu có.',
            'discount_code.unique' => 'Mã giảm giá đã tồn tại trong chương trình khuyến mãi này.',
        ];
    }
}