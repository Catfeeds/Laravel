<?php

namespace App\Http\Requests;

class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => ['between:1,200', 'regex:/^(?:[\x{4e00}-\x{9fa5}]+)(?:·[\x{4e00}-\x{9fa5}]+)*$|^[a-zA-Z0-9]+\s?[\.·\-()a-zA-Z]*[a-zA-Z]+$/u'],
                    'type' => 'required|string|in:designer,party',
                    'phone' => 'required|phone:CN',
                    'password' => 'required|string|min:6|max:30',
                    'verification_code' => 'required|string'
                ];
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name' => ['between:1,200', 'regex:/^(?:[\x{4e00}-\x{9fa5}]+)(?:·[\x{4e00}-\x{9fa5}]+)*$|^[a-zA-Z0-9]+\s?[\.·\-()a-zA-Z]*[a-zA-Z]+$/u'],
                    'title' => 'max:50',
                    'email' => 'email',
                    'introduction' => 'max:200',
                    'avatar_id' => 'exists:uploads,id,type,avatar,user_id,'.$userId,
                    'id_card_id' => 'exists:uploads,id,type,id_card,user_id,'.$userId,
                    'id_number' => 'string|max:25',
                    'bank_name' => 'string|max:100',
                    'bank_card_number' => 'string|max:100',
                    'account_name' => 'string|max:100',
                    'qualification_urls' => 'array',
                    'qualification_urls.*' => 'distinct|url'
                ];
        }
    }

    public function messages()
    {
        return [
            'avatar_id.exists' => '图片id不存在',
            'id_card_id.exists' => '图片id不存在'
        ];
    }
}
