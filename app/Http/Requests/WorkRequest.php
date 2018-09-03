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
            'photo_urls' => 'array',
//            'photo_urls.*' => 'distinct|exists:uploads,path,type,work_photo,user_id,'.$userId,
            'photo_urls.*' => 'distinct|url',
            'visible_range' => 'required|string|in:public,private'
        ];
    }

    public function messages()
    {
        return [
            'photo_urls.*.distinct' => '图片链接不能重复',
            'photo_urls.*.exists' => '图片链接不存在'
        ];
    }
}
