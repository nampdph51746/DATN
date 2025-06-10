<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8'],
        'role_id' => ['required', 'exists:roles,id'],
        'customer_rank_id' => ['required', 'exists:customer_ranks,id'],
        'phone_number' => ['nullable', 'string', 'max:20'],
        'address' => ['nullable', 'string'],
        'date_of_birth' => ['nullable', 'date'],
        'status' => ['required', 'in:active,inactive,suspended'],
        'avatar_url' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Vui lòng nhập tên người dùng.',
        'name.string' => 'Tên người dùng phải là một chuỗi ký tự.',
        'name.max' => 'Tên người dùng không được vượt quá 255 ký tự.',

        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.email' => 'Địa chỉ email không hợp lệ.',
        'email.max' => 'Email không được vượt quá 255 ký tự.',
        'email.unique' => 'Email này đã được sử dụng.',

        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',

        'role_id.required' => 'Vui lòng chọn vai trò.',
        'role_id.exists' => 'Vai trò được chọn không hợp lệ.',

        'customer_rank_id.required' => 'Vui lòng chọn hạng khách hàng.',
        'customer_rank_id.exists' => 'Hạng khách hàng được chọn không hợp lệ.',

        'phone_number.string' => 'Số điện thoại phải là chuỗi ký tự.',
        'phone_number.max' => 'Số điện thoại không được vượt quá 20 ký tự.',

        'address.string' => 'Địa chỉ phải là chuỗi ký tự.',

        'date_of_birth.date' => 'Ngày sinh không đúng định dạng ngày tháng.',

        'status.required' => 'Vui lòng chọn trạng thái tài khoản.',
        'status.in' => 'Trạng thái không hợp lệ. Chỉ chấp nhận: active, inactive, suspended.',

        'avatar_url.image' => 'Ảnh đại diện phải là tệp hình ảnh.',
        'avatar_url.mimes' => 'Ảnh đại diện chỉ chấp nhận định dạng: jpg, jpeg, png.',
        'avatar_url.max' => 'Ảnh đại diện không được lớn hơn 2MB.',
    ];
}

}
