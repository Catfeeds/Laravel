<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required_without:email|phone:CN',
            'email' => 'required_without:phone|email',
            'type' => 'required|in:designer,client',
            'code' => 'required|size:6',
            'password' => 'required|string'
        ];
    }
}
