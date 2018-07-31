<?php

namespace App\Http\Requests;

class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content' => 'required|string|max:200'
        ];
    }
}
