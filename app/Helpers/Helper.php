<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Helper
{

    public static function get_guard()
    {
        return Auth::getDefaultDriver();
        // if (Auth::guard('admin')->check()) {
        //     return "admin";
        // } elseif (Auth::guard('store')->check()) {
        //     return "store";
        // } elseif (Auth::guard('vendor')->check()) {
        //     return "vendor";
        // }
    }

    public static function imageUrl($image)
    {
        return ($image != null) ? cdn(config('globalconstants.imageSize')['path'] . '/' . $image) : null;
    }

    public static function cdn($asset)
    {
        //Check if we added cdn's to the config file
        if (!env('CDN_ENABLED', false)) {
            return Storage::disk('public')->url($asset);
        } else {
            return Storage::disk('s3')->url(env('CDN_FILE_DIR', 'dev/test/') . $asset);
        }
    }

    public static function checkLogin($email, $password)
    {
        //login url from config
        $response = Http::post(config('settings.login_api_url'), [
            'username' => $email,
            'password' => $password,
        ]);

        return $response->json();
    }

    public static function getPaymentStatus($status)
    {
        switch ($status) {
            case 0:
                return "Pending";
                break;
            case 1:
                return "Paid";
                break;
            default:
                return "Failed";
                break;
        }
    }

    public static function getPaymentType($type)
    {
        return trans("api.payment_methods.{$type}");
    }

    public static function encrypt($plainText,$key)
    {
        $key =Helper::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    public static function decrypt($encryptedText,$key)
    {
        $key =Helper::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText =Helper::hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
    //*********** Padding Function *********************

    public static function pkcs5_pad ($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    //********** Hexadecimal to Binary function for php 4.0 version ********

    public static function hextobin($hexString) 
     { 
            $length = strlen($hexString); 
            $binString="";   
            $count=0; 
            while($count<$length) 
            {       
                $subString =substr($hexString,$count,2);           
                $packedString = pack("H*",$subString); 
                if ($count==0)
            {
                $binString=$packedString;
            } 
                
            else 
            {
                $binString.=$packedString;
            } 
                
            $count+=2; 
            } 
            return $binString; 
          } 

}
