<?php

namespace App\Http\Requests;

class ActivityRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'content' => 'required_without:photo_image_ids|max:200',
            'photo_image_ids' => 'required_without:content|array',
            'photo_image_ids.*' => 'distinct|exists:uploads,id,type,activity_photo,user_id,'.$userId
        ];
    }

    public function messages()
    {
        return [
            'photo_image_ids.*.distinct' => '图片id不能重复',
            'photo_image_ids.*.exists' => '图片id不存在'
        ];
    }
}
