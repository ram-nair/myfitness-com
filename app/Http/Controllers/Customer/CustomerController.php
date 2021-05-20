<?php

namespace App\Http\Controllers\Customer;

use App\Events\TwilioRegister;
use App\Http\Controllers\Controller;
use App\Http\Resources\FavouriteCollection;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\UserAddressResource;
use App\Notification;
use App\StoreFavourite;
use App\UserAddress;
use Illuminate\Http\Request;
use Validator;

class CustomerController extends Controller
{

    public function listUserAddress(Request $request)
    {
        return successResponse(trans('api.address.list'), UserAddressResource::collection($request->user()->address));
    }

    //update device ids for push notifications
    public function updateDeviceId(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'deviceId' => 'required',
            'deviceType' => 'required',
            'bindingType' => 'required',
        ]);

        if ($validator->fails()) {
            return errorResponse(trans('api.error_required_fields'));
        }

        //$userExists = Auth::guard('api')->user();
        $userExists = $request->user();
        if ($userExists->deviceId != $request->deviceId) {
            $userExists->twilioIdentity = 'Customer-' . $request->deviceType . '-' . $userExists->id;
            $userExists->deviceId = $request->deviceId;
            $userExists->deviceType = $request->deviceType;

            event(new TwilioRegister($userExists, $request->bindingType, 'customer', true));
        }

        if (!empty($request->versionNumber)) {
            //App versionNumber update
            $userExists->versionNumber = $request->versionNumber;
        }

        if (!empty($request->deviceDetails)) {
            $userExists->deviceDetails = json_encode($request->deviceDetails);
        }

        $userExists->save();

        return successResponse(trans('api.device_token_updated'));
    }

    public function storeUserAddress(Request $request, UserAddress $address)
    {
        $rules = [
            'latitude' => 'required',
            'longitude' => 'required',
        ];
        $request->validate($rules);
        $data = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location' => $request->location,
            'apartment' => $request->apartment,
            'building_name' => $request->building_name,
            'address_name' => $request->address_name,
            'instruction' => $request->instruction,
        ];
        $customer = $request->user();
        if ($address && $address->id) {
            $customer->address()->where('id', $address->id)->update($data);
        } else {
            $customer->address()->save(new UserAddress($data));
        }
        return successResponse(trans('api.address.saved'), UserAddressResource::collection($customer->address));
    }

    public function deleteUserAddress(Request $request, UserAddress $address)
    {
        $address->delete();
        return successResponse(trans('api.address.delete'), UserAddressResource::collection($request->user()->address));
    }

    public function favouriteStores(Request $request)
    {
        return new FavouriteCollection($request->user()->favouriteStores);
    }

    public function updateFavouriteStore(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);
        $favourite = $request->user()->favouriteStores()->where('store_id', $request->store_id)->first();
        if ($favourite && $favourite->exists()) {
            $favourite->forceDelete();
        } else {
            $favourite = $request->user()->favouriteStores()->save(new StoreFavourite(['store_id' => $request->store_id]));
        }
        return new FavouriteCollection($request->user()->favouriteStores);
    }

    public function userNotifications(Request $request)
    {
        $limit = $request->limit ?? 20;
        $query = Notification::where('userId', $request->user()->id);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $result = $query->latest()->paginate($limit);
        return response()->json([
            'statusCode' => 200,
            'message' => trans('api.customer.notifications'),
            'data' => $result->isEmpty() ? null : new NotificationCollection($result),
        ]);
    }

    public function notificationDetails(Request $request, Notification $notification)
    {
        return response()->json([
            'statusCode' => 200,
            'message' => trans('api.customer.notification'),
            'data' => !empty($notification->data) ? $notification->data : null,
        ]);
    }
}
