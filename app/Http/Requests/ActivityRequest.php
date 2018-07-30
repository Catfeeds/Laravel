<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'content' => 'required|string|max:200',
            'photo_image_ids' => 'array',
            'photo_image_ids.*' => 'distinct|exists:images,id,type,activity,user_id,'.$userId
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
