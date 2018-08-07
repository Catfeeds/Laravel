<?php

namespace App\Http\Requests;

class ProjectApplicationRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'remark' => 'nullable|string|max:300',
            'application_file_id'     => 'nullable|integer|exists:uploads,id,type,application_file,user_id,' . $userId,
        ];
    }

    public function messages()
    {
        return [
            'application_file_id.exists' => '文件id不存在'
        ];
    }
}
