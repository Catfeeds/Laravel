<?php

namespace App\Http\Requests;

class ProjectInvitationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'refusal_cause' => 'required|string|max:300'
        ];
    }
}
