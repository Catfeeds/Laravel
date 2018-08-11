<?php

namespace App\Http\Requests;

class ActivityRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'content' => 'required_without:photo_ids|max:200',
            'photo_ids' => 'required_without:content|array',
            'photo_ids.*' => 'distinct|exists:uploads,id,type,activity_photo,user_id,'.$userId
        ];
    }

    public function messages()
    {
        return [
            'photo_ids.*.distinct' => '图片id不能重复',
            'photo_ids.*.exists' => '图片id不存在'
        ];
    }
}
