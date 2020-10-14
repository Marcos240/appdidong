<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\ValidAddress;

class UpdateUserProfileRequest extends Request
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'passcodeConfirm' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập tên',
            'passcodeConfirm.required' => 'Bạn cần nhập mật khẩu xác nhận',
        ];
    }
}
