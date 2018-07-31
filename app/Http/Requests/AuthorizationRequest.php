<?php

namespace App\Http\Requests;

class AuthorizationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|phone:CN',
            'password' => 'required|string'
        ];
    }
}
