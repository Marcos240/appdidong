<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'passcode' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'Bạn cần nhập username',
            'passcode.required' => 'Bạn cần nhập mật khẩu đăng nhập'
        ];
    }
}
