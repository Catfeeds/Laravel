<?php

namespace App\Http\Requests;

class AuthorizationRequest extends FormRequest
{
    public function rules()
    {
        return [
//            'phone' => 'required|phone:CN',
            'identifier' => 'required|string',
            'password' => 'required|string',
            'type' => 'required|string|in:designer,client'
        ];
    }
}
