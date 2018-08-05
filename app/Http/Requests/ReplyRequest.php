<?php

namespace App\Http\Requests;

class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'replyee_id' => 'integer|exists:users,id',
            'content'    => 'required|string|max:200'
        ];
    }

    public function messages()
    {
        return [
            'replyee_id.exists' => '被回复用户不存在'
        ];
    }
}
