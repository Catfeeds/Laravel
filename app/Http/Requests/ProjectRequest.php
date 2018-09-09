<?php

namespace App\Http\Requests;

class ProjectRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        switch ($this->method()) {
            case 'POST':
                return [
                    'title'            => 'required|string|max:200',
                    'types'            => 'required|array',
                    'types.*'          => 'string|max:50',
                    'features'         => 'required|array',
                    'features.*'       => 'string|max:50',
                    'keywords'         => 'nullable|array|max:10',
                    'keywords.*'       => 'distinct|string|max:50',
                    'depth'            => 'required|string',
                    'delivery_time'    => 'required|string|max:50',
                    'payment'          => 'required|string|max:200',
                    'description'      => 'required|string',
                    'project_file_url' => 'nullable|string|exists:uploads,path,type,project_file,user_id,' . $userId,
                    'find_time'        => 'required|string|max:60',
                    'mode'             => 'required|string|in:free,invite,specify',
                    'remark'           => 'nullable|string',
                    'invited_designer_ids' => 'array|required_if:mode,invite,specify',
                    'invited_designer_ids.*' => 'exists:users,id,type,designer'
                ];
            case 'PATCH':
                return [
                    'title'            => 'required|string|max:200',
                    'types'            => 'required|array',
                    'types.*'          => 'string|max:50',
                    'features'         => 'required|array',
                    'features.*'       => 'string|max:50',
                    'keywords'         => 'nullable|array|max:10',
                    'keywords.*'       => 'distinct|string|max:50',
                    'depth'            => 'required|string',
                    'delivery_time'    => 'required|string|max:50',
                    'payment'          => 'required|string|max:200',
                    'description'      => 'required|string',
                    'project_file_url' => 'nullable|string|exists:uploads,path,type,project_file,user_id,' . $userId,
                    'find_time'        => 'required|string|max:60',
                    'remark'           => 'nullable|string',
                    're_review'        => 'boolean' // 是否重新审核
                ];
        }
    }

    public function messages()
    {
        return [
            'project_file_url.exists' => '文件path不存在'
        ];
    }
}
