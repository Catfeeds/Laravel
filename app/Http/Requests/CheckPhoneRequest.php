<?php

namespace App\Http\Requests;

class CheckPhoneRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required|string|in:designer,client',
            'phone' => 'required|phone:CN'
        ];
    }
}
