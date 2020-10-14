<?php

namespace App\Http\Requests;

use  App\Http\Requests\Request;

class UpdatePasscodeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currentPasscode' => 'required',
            'passcode' => 'required',
            'passcodeConfirm' => 'required|same:passcode'
        ];
    }
    public function messages()
    {
        return [
            'currentPasscode.required' => 'Bạn cần nhập mật khẩu hiện tại',
            'passcode.required' => 'Bạn cần nhập mật khẩu mới',
            'passcodeConfirm.require' => 'Bạn cần xác nhận mật khẩu mới',
            'passcodeConfirm.same' => 'Mật khẩu xác nhận không trùng khớp'
        ];
    }
}
