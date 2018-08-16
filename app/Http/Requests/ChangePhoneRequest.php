<?php

namespace App\Http\Requests;

class ChangePhoneRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|phone:CN',
            'code' => 'required|size:6'
        ];
    }
}
