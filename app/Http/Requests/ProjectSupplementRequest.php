<?php

namespace App\Http\Requests;

class ProjectSupplementRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'supplement_description' => 'required|string',
            'supplement_file_id'     => 'nullable|integer|exists:uploads,id,type,project_file,user_id,' . $userId,
        ];
    }

    public function messages()
    {
        return [
            'supplement_file_id.exists' => '文件id不存在'
        ];
    }
}
