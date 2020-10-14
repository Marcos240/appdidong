<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RatingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => 'required | numeric | min:0 | max:5'
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Bạn cần chọn số điểm xếp hạng',
            'rating.numeric' => 'Số điểm xếp hạng không hợp lệ',
            'rating' => "Số điểm xếp hạng phải từ 0-5 đ"
        ];
    }
}
