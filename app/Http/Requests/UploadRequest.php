<?php

namespace App\Http\Requests;

class UploadRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:avatar,activity_photo,project_file',
        ];
        switch ($this->type) {
            case 'avatar':
            case 'activity_photo':
                $rules['file'] = 'required|image';
                break;
            case 'project_file':
                $rules['file'] = 'required|file|max:10240';
                break;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            // 'file.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
            'file.max' => '文件大小不得超过10M'
        ];
    }
}
