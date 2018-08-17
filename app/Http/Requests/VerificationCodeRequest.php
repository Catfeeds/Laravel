<?php

namespace App\Http\Requests;

class VerificationCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|phone:CN',
            'type'  => 'required|in:register,resetPassword,changePhone'
        ];
    }

    public function messages()
    {
        return [
            'phone' => '手机号格式不合法'
        ];
    }
}
