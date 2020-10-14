<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApartmentModificationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user->role === 'landlord';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function failedAuthorization() {
        throw new HttpResponseException(response()->json([
            'status' => 'fail',
            'messages' => 'Bạn phải là chủ trọ để có thể thực hiện thao tác này'
        ], 403));
    }
}
