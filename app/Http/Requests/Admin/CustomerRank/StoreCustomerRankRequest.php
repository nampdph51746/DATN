<?php

namespace App\Http\Requests\Admin\CustomerRank;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRankRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:customer_ranks,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'min_points_required' => ['required', 'integer', 'min:0'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Vui lòng nhập tên hạng khách hàng.',
        'name.string' => 'Tên hạng khách hàng phải là chuỗi ký tự.',
        'name.max' => 'Tên hạng khách hàng không được vượt quá 255 ký tự.',
        'name.unique' => 'Tên hạng khách hàng đã tồn tại.',

        'description.string' => 'Mô tả phải là chuỗi ký tự.',
        'description.max' => 'Mô tả không được vượt quá 500 ký tự.',

        'min_points_required.required' => 'Vui lòng nhập điểm tối thiểu cần có.',
        'min_points_required.integer' => 'Điểm tối thiểu phải là số nguyên.',
        'min_points_required.min' => 'Điểm tối thiểu không được nhỏ hơn 0.',

        'discount_percentage.required' => 'Vui lòng nhập phần trăm giảm giá.',
        'discount_percentage.numeric' => 'Phần trăm giảm giá phải là số.',
        'discount_percentage.min' => 'Phần trăm giảm giá không được nhỏ hơn 0.',
        'discount_percentage.max' => 'Phần trăm giảm giá không được lớn hơn 100.',
    ];
}

}
