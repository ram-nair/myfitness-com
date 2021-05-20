<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Auth;
use DB;

class PasswordResetController extends ApiController {

    use SendsPasswordResetEmails,
        ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    public function __construct() {
        $this->middleware('guest:api');
    }

    protected function guard() {
        return Auth::guard('api');
    }

    public function resetPassword(Request $request) {
        $rules = [
            'email' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return $this->sendResetLinkEmail($request);
        }
        return errorResponse(trans('api.user_not_exist'));
    }

    protected function sendResetLinkResponse(Request $request, $response) {
        return successResponse(trans('api.password_reset_send'));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response) {
        return errorResponse(trans('api.password_reset_failed'));
    }

}
