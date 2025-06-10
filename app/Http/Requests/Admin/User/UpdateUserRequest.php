<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'role_id' => ['required', 'exists:roles,id'],
            'customer_rank_id' => ['required', 'exists:customer_ranks,id'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }

    public function messages(): array
{
    return [
        'role_id.required' => 'Vui lòng chọn vai trò.',
        'role_id.exists' => 'Vai trò được chọn không hợp lệ.',

        'customer_rank_id.required' => 'Vui lòng chọn hạng khách hàng.',
        'customer_rank_id.exists' => 'Hạng khách hàng được chọn không hợp lệ.',

        'status.required' => 'Vui lòng chọn trạng thái tài khoản.',
        'status.in' => 'Trạng thái không hợp lệ. Chỉ chấp nhận: active, inactive, suspended.',
    ];
}

}
