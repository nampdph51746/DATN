<?php

namespace App\Http\Requests\CustomerRankPromotions;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRankPromotionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_rank_id' => 'required|exists:customer_ranks,id',
            'promotion_id' => 'required|exists:promotions,id',
            'description' => 'nullable|string|max:500',
            'discount_code' => 'nullable|string|max:50',
        ];
    }

    // Thông báo bằng tiếng Việt
    public function messages()
    {
        return [
            'customer_rank_id.required' => 'Vui lòng chọn hạng khách hàng.',
            'customer_rank_id.exists' => 'Hạng khách hàng không tồn tại.',
            'promotion_id.required' => 'Vui lòng chọn chương trình khuyến mãi.',
            'promotion_id.exists' => 'Chương trình khuyến mãi không tồn tại.',
            'discount_code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'discount_code.string' => 'Mã giảm giá phải là một chuỗi ký tự.',
            'description.string' => 'Mô tả phải là một chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ];
    }
}