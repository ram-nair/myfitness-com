<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Traits\NotificationTraits;
//use App\Traits\PaymentTraits;

class PaymentHandlerController extends Controller
{
    //use PaymentTraits;

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
    public function index(Request $request){
        $url = "https://secure.ccavenue.ae/transaction/getRSAKey";
        $fields = array(
                'access_code'=>config('ccavenue.accessCode'), //put access code here
                'order_id'=>$request->order_id
        );

        $postvars='';
        $sep='';
        foreach($fields as $key=>$value)
        {
                $postvars.= $sep.urlencode($key).'='.urlencode($value);
                $sep='&';
        }

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certs/cacert.pem'));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        echo $result;
        exit;

    }

    public function ccavResponseHandler(Request $request){
        $workingKey = config('ccavenue.accessKey');  //Working Key should be provided here.

        $encResponse = $request->encResp;   //This is the response sent by the CCAvenue Server

        $rcvdString = decrypt($encResponse, $workingKey);  //Crypto Decryption used as per the specified working key.

        echo $rcvdString;
        exit;

    }

    function decrypt($encryptedText, $key) {
        $key = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    function hextobin($hexString) {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }


}
