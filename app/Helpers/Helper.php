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
}
