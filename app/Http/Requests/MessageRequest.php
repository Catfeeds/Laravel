<?php

namespace App\Http\Requests;

class MessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'to' => 'required|exists:users,id',
            'body' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'to.exists' => '接收人不存在'
        ];
    }
}
