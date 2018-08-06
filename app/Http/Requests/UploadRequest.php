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
                $rules['file'] = 'required|mimes:jpeg,jpg,bmp,png,gif';
                break;
            case 'activity_photo':
                $rules['file'] = 'required|mimes:jpeg,jpg,bmp,png,gif';
                break;
            case 'project_file':
                // TODO
                break;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            // 'image.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
        ];
    }
}
