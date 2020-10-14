<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\ValidAddress;
use Illuminate\Validation\Rule;


class SignupRequest extends Request
{
 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'name' => 'required',
            'passcode' => 'required',
            'passcodeConfirm' => 'required|same:passcode',
            'phone' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'Bạn cần nhập Username',
            'username.unique' => 'Username này đã được sử dụng rồi',
            'email.email' => 'Email không hợp lệ',
            'email.required' => 'Bạn cần nhập email',
            'email.unique' => 'Email này đã được sử dụng rồi',
            'name.required' => 'Bạn cần nhập tên',
            'passcode.required' => 'Bạn cần nhập mật khẩu mới',
            'passcodeConfirm.required' => 'Bạn cần xác nhận mật khẩu mới',
            'passcodeConfirm.same' => 'Mật khẩu mới không trùng khớp',
            'phone.required' => "Bạn cần nhập số điện thoại"
        ];
    }
}
