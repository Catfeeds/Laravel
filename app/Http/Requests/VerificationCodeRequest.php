<?php

namespace App\Http\Requests;

class VerificationCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required_without:email|phone:CN',
            'email' => 'required_without:phone|email',
            'user_type' => 'required_with:phone|in:party,designer',
            'action_type'  => 'required|in:register,resetPassword,changePhone'
        ];
    }
}
