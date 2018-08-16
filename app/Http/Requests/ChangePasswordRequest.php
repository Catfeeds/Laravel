<?php

namespace App\Http\Requests;

class ChangePasswordRequest extends FormRequest
{

    public function rules()
    {
        return [
            'password' => 'required|string',
            'new_password' => 'required|string|min:6|max:30'
        ];
    }
}
