<?php

namespace App\Http\Requests;

class ProjectDeliveryRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'remark'    => 'nullable|string|max:5000',
            'file_url'  => 'required|string|exists:uploads,path,type,delivery_file,user_id,' . $userId,
        ];
    }
}
