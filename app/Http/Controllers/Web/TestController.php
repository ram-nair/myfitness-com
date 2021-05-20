<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Traits\NotificationTraits;
use App\Traits\PaymentTraits;

class TestController extends Controller
{
    use NotificationTraits, PaymentTraits;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id= !empty($request->id) ? $request->id : '65cb8c70-d17e-11ea-8795-e121237be871';

        $user = \App\User::find($id);

        $type = config('globalconstants.NOTIFICATION_MESSAGE_TYPE.ORDER_PLACED');

        $data['orderId'] =23;
        $data['amount'] =400;

        $this->createOrderPush($user, $type, $data);

    }

    public function charge(){
        $this->chargeCustomer('SI2022110112413', 'Chref'.time(), 2);
    }

}
