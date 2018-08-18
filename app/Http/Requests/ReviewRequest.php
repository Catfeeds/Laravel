<?php

namespace App\Http\Requests;

class ReviewRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id'   => 'integer|exists:users,id',
            'content'    => 'required|string|max:300'
        ];
    }

    public function messages()
    {
        return  [
            'user_id.exists' => '被评价用户id不存在',
            'content.max' => '评价内容最多300字'
        ];
    }
}
