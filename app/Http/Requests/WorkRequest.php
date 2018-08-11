<?php

namespace App\Http\Requests;

class WorkRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'distinct|exists:uploads,id,type,work_photo,user_id,'.$userId,
            'photo_urls' => 'array',
            'photo_urls.*' => 'distinct|exists:uploads,path,type,work_photo,user_id,'.$userId,
            'visible_range' => 'required|string|in:public,private'
        ];
    }

    public function messages()
    {
        return [
            'photo_ids.*.distinct' => '图片id不能重复',
            'photo_ids.*.exists' => '图片id不存在',
            'photo_urls.*.distinct' => '图片链接不能重复',
            'photo_urls.*.exists' => '图片链接不存在'
        ];
    }
}
