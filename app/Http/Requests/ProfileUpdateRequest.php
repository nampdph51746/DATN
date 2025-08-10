<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => [
            //     'required',
            //     'string',
            //     'lowercase',
            //     'email',
            //     'max:255',
            //     Rule::unique(User::class)->ignore($this->user()->id),
            // ],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone_number')->ignore($this->user()->id),
            ],
            'date_of_birth' => ['required', 'date', 'before:today'],
        ];
    }
    public function messages(): array
{
    return [
        'name.required' => 'Họ và tên là bắt buộc.',
        'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
        'phone_number.required' => 'Số điện thoại là bắt buộc.',
        'phone_number.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        'phone_number.unique' => 'Số điện thoại đã được sử dụng.',
        'date_of_birth.required' => 'Ngày sinh là bắt buộc.',
        'date_of_birth.date' => 'Ngày sinh không hợp lệ.',
        'date_of_birth.before' => 'Ngày sinh phải trước hôm nay.',
    ];
}

}