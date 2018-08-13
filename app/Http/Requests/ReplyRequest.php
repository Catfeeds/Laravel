<?php

namespace App\Http\Requests;

class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'reply_id'   => 'integer|exists:replies,id',
            'content'    => 'required|string|max:200'
        ];
    }
}
