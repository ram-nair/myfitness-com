<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller {

    public function __construct() {
        $this->middleware(['api']);
    }

    public function addCard(Request $request) {

       $rules = [
            'cardDetails' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        $data = [];
        if ($validator->fails()) {
            $data['errorMessages'] = $validator->messages();
            return errorResponse(trans('api.required_fields'),$data);
        }

        $user = $request->user();
        $cardDetails = $request->cardDetails;


        $cardExist = $user->paymentMethods()->where('si_reference', $cardDetails['si_ref_no'])->first();
        if ($cardExist) {
            if ($cardExist->status == 2) {
                //Card is in ccavenue pay but deleted from our db.So enable it
                $cardExist->status = 1;
                $cardExist->save();
                Arr::set($cardExist, 'response_data', json_decode($cardExist->responseData));
                return successResponse(trans('api.cards.card_added'), $cardExist);
            }
            return errorResponse(trans('api.cards.card_exist'));
        } else {
            if (isset($cardDetails['si_ref_no'])) {
                $newCard = new \App\UserPaymentMethod();
                $newCard->user_id = $user->id;
                $newCard->si_reference = $cardDetails['si_ref_no'];
                $newCard->response_data = json_encode($cardDetails);
                $newCard->save();

                //During first card add - set it as default - defaultCard
                $userCardsCount = \App\UserPaymentMethod::where('user_id', $user->id)->where('status', 1)->count();

                if ($userCardsCount == 1) {
                    $newCard->default_card = config('globalconstants.CARD_TYPE.DEFAULT');
                    $newCard->save();
                }

                Arr::set($newCard, 'response_data', json_decode($newCard->response_data));

                return successResponse(trans('api.cards.card_added'), $newCard);
            } else {
                return errorResponse(trans('api.cards.invalid_card'));
            }
        }

    }


    public function getPaymentMethods(Request $request) {
        $user = $request->user();

        $cards = $user->paymentMethods()->where('status', config('globalconstants.USER_CARD_STATUS.ENABLED'))->get();

        $today = date('Y-m-d');
        /*$cards->filter(function($item) use($today) {
            /*$expiryStatus = false;
            $expiryData = json_decode($item->responseData);
            if (!empty($expiryData->expiryDate)) {
                $date = explode("/", $expiryData->expiryDate);
                $dateString = date('m') . '-' . $date[0] . '-' . $date[1];
                $lastDateOfMonth = date("Y-m-t", strtotime($dateString));

                if (strtotime($today) > strtotime($lastDateOfMonth)) {
                    $expiryStatus = true;
                }
            }

            $item->expiryStatus = $expiryStatus;


            return $item->responseData = json_decode($item->responseData);
        });*/

        return successResponse('', $cards);
    }


   public function setDefaultCard(Request $request) {
        $rules = [
              'card_id' => 'required'
          ];
        $validator = Validator::make($request->all(), $rules);
        $data = [];
        if ($validator->fails()) {
            $data['errorMessages'] = $validator->messages();
            return errorResponse(trans('api.required_fields'),$data);
        }

        $user = $request->user();
        if(empty($user)){
            return errorResponse(trans('api.invalid_user'));
        }

        $cardExist = \App\UserPaymentMethod::where('user_id', $user->id)
                                             ->where('id', $request->card_id)
                                             //->where('status', 1)
                                             ->first();

        if(empty($cardExist)){
           return errorResponse(trans('api.cards.card_not_exist'));
        }

        //chnge old card status
        \App\UserPaymentMethod::where('user_id', $user->id)
                                ->where('status', 1)
                                ->update(['default_card' => 0]);

        //set this card default
        $cardExist->default_card = 1;
        $cardExist->save();

        return successResponse(trans('api.cards.set_default_success'));

    }
    public function disableCard(Request $request) {
      $rules = [
            'card_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        $data = [];
        if ($validator->fails()) {
            $data['errorMessages'] = $validator->messages();
            return errorResponse(trans('api.required_fields'),$data);
        }

        $user = $request->user();
        if(empty($user)){
            return $this->errorResponse(trans('api.invalid_user'));
        }

        $cardExist = \App\UserPaymentMethod::where('user_id', $user->id)
                                             ->where('id', $request->card_id)->first();

        if(empty($cardExist)){
           return errorResponse(trans('api.cards.card_not_exist'));
        }


        //$userCard = \App\UserPaymentMethod::where('userId', $request->user_id)->where('providerReference', $request->provider_reference)->where('status', 1)->first();
        if($cardExist->default_card == 1){
           return errorResponse(trans('api.cards.unable_to_delete_default_card'));
        }

        $cardExist->status = 2;
        $cardExist->save();

        return successResponse(trans('api.cards.card_disabled'));

      }

}
