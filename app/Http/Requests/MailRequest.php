<?php

namespace App\Http\Requests;

class MailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}
