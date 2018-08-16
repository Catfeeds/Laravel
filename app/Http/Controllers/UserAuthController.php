<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;

class UserAuthController extends Controller
{
    public function changePassword(ChangePasswordRequest $request) {
        $currentUser = $this->user();
        $credentials['phone'] = $currentUser->phone;
        $credentials['password'] = $request->password;
        if (!\Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorBadRequest(__('Wrong original password'));
        }
        $currentUser->update([
            'password' => bcrypt($request->new_password)
        ]);
        return $this->response->noContent();
    }
}
