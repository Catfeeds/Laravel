<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|phone:CN',
            'type' => 'required|in:designer,party',
            'code' => 'required|size:6',
            'password' => 'required|string'
        ];
    }
}
