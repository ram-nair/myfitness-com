<?php

namespace App\Http\Controllers\Api\V1;

use App\Community;
use App\Events\TwilioRegister;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Validator;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|email',
            'password' => 'required|min:6',
            'bindingType' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        // $user = User::first();
        // $token = $user->createToken('api-token')->plainTextToken;
        // return successResponse(trans('api.success'), (new CustomerResource($user))->token($token));

        // check if user exits in the main site
        // $user = User::first();
        // $token = $user->createToken('api-token')->plainTextToken;
        // return successResponse(trans('api.success'), (new CustomerResource($user))->token($token));

        $response = Http::asForm()->withHeaders([
            'apiKey' => config('settings.login_api_key'),
        ])->post(config('settings.login_api_url'), [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $loginResponse = $response;
        if ($loginResponse && $loginResponse['status'] == 'success') {
            $data = $loginResponse['data'];
            //check if user email exits in users table
            $updatedData = [
                //'name' => $data['first_name']." ".$data['last_name'],
                //'id_login' => $data['id_login'],
                'email' => $data['email'],
                'password' => Hash::make($request->password),
                'provis_user_id' => $data['id_user'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'photo' => $data['photo'],
                'gender' => $data['gender'],
                'precinct' => $data['precinct'],
                'units' => $data['units'],
            ];

            $user = User::updateOrCreate(['email' => $data['email']], $updatedData);
            //create or add the community table

            if (!empty($user)) {
                $user->deviceId = $request->deviceId;
                $user->deviceType = $request->deviceType;
                $user->twilioIdentity = 'Customer-' . $request->deviceType . '-' . $user->id;
                $user->save();

                if (!empty($request->deviceId)) {
                    event(new TwilioRegister($user, $request->bindingType, 'customer'));
                }
            }

            $user_comm = [];
            foreach ($data['community'] as $community) {
                $newCommunity = Community::updateOrCreate(['id_community' => $community['id_community']], [
                    'name' => $community['community_name'],
                    'banner_url' => $community['community_banner_url'],
                    'id_community' => $community['id_community'],
                ]);
                $user_comm[] = $newCommunity->id;
            }
            //create or update the user community
            $user->community()->sync($user_comm);
            $token = $user->createToken($request->deviceId ?? "no-deviceId")->plainTextToken;
            return successResponse(trans('api.success'), (new CustomerResource($user))->token($token));
        } else {
            return errorResponse(trans('api.invalid_credentials'));
        }
    }
}
