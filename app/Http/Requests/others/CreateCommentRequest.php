<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCommentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required | max:100'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Bạn cần nhập nội dung bình luận',
            'text.max' => 'Bình luận chỉ có thể chứa tối đa 100 kí tự'
        ];
    }
}
