<?php

namespace App\Http\Requests;

class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'replyee_id' => 'integer|exists:users,id',
            'reply_id'   => 'integer|exists:replies,id',
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
