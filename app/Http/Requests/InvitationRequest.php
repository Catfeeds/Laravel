<?php

namespace App\Http\Requests;

class InvitationRequest extends FormRequest
{
    public function rules()
    {
        $userId = \Auth::guard('api')->id();
        return [
            'invited_user_id' => 'required|exists:users,id|not_in:'.$userId,
        ];
    }

    public function messages()
    {
        return  [
            'invited_user_id.exists' => '被邀请用户id不存在',
            'invited_user_id.not_in' => '不能邀请自己'
        ];
    }
}
