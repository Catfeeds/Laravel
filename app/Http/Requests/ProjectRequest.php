<?php

namespace App\Http\Requests;

class ProjectRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'title'           => 'required|string|max:200',
            'types'           => 'required|array',
            'types.*'         => 'string|max:50',
            'features'        => 'required|array',
            'features.*'      => 'string|max:50',
            'area'            => 'required|string',
            'delivery_time'   => 'required|string|max:50',
            'payment'         => 'required|string|max:200',
            'description'     => 'required|string',
            'project_file_id' => 'nullable|integer|exists:uploads,id,type,project_file,user_id,' . $userId,
            'find_time'       => 'required|string|max:60',
            'remark'          => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'project_file_id.exists' => '文件id不存在'
        ];
    }
}
