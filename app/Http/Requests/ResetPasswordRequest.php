<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|phone:CN',
            'code' => 'required|size:6',
            'password' => 'required|string'
        ];
    }
}
