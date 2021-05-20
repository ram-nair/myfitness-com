<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\UserActiveToken;
use Auth;
use App\Traits\ImageTraits;
use File;
use Storage;

//use Mail;

class UserController extends Controller
{

    use ImageTraits;

    public function __construct()
    {
        $this->middleware('api');
    }

    function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt(trim($request->password));

        $user->save();

        $token = auth('api')->login($user);

        $userActiveToken = new UserActiveToken();
        $userActiveToken->user_id = $user->id;
        $userActiveToken->token = $token;
        $userActiveToken->save();

        $data = [
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];
        return successResponse(trans('api.registration_success'), $data);
    }

    function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return errorResponse(trans('api.invalid_credentials'));
        }

        $user = auth('api')->user();
        $user->access_token = $token;

        //Invalidate old token and save current token
        $userActiveToken = $user->activeToken;

        if ($userActiveToken) {
            try {
                auth('api')->setToken($userActiveToken->token)->invalidate(true);
            } catch (\Exception $e) {
            }
        } else {
            $userActiveToken = new UserActiveToken();
            $userActiveToken->user_id = $user->id;
        }
        $userActiveToken->token = $token;
        $userActiveToken->save();

        return $this->profile();
    }

    public function logout(Request $request)
    {
        // $tokens = Auth::user()->currentAccessToken()->id;
        $request->user()->currentAccessToken()->delete();
        return successResponse(trans('api.success'));
    }

    public function uploadProfilePicture(Request $request)
    {
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = auth('api')->user();
        $path = 'user/profile/';
        $singleImage = $this->singleImage($request->file('image'), $path, $user->id);

        if ($singleImage) {
            if (!empty($user->image)) {
                if (!env('CDN_ENABLED', false)) {
                    File::delete(storage_path('app/public/' . $path) . $user->image);
                } else {
                    Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/test/') . $path . $user->image);
                }
            }
            $user->image = $singleImage;
            $user->save();
            $data['image'] = cdn($path . $singleImage);
            return successResponse(trans('api.success'), $data);
        }
        return errorResponse(trans('api.upload_failed'));
    }

    public function profile()
    {
        $user = auth('api')->user();
        $user->image = !empty($user->image) ? cdn('user/profile/' . $user->image) : null;
        return successResponse(trans('api.success'), array_except($user->toArray(), ['created_at', 'updated_at', 'active_token']));
    }
}
