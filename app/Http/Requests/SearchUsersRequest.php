<?php

namespace App\Http\Requests;

class SearchUsersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'invite_to_review' => 'nullable', // 这条规则其实没用，只是为了标识可能有的参数
            'keyword' => 'nullable|string',
            'professional_fields' => 'array',
            'professional_fields.*' => 'string'
        ];
    }
}
