<?php

namespace App\Myadmin;

use Auth;
use Helper;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MyMenuFilter implements FilterInterface
{

    public function transform($item)
    {
        if (isset($item['route']) && $item['route'] == "admin.dashboard") {
            $item['route'] = \Helper::get_guard() . ".dashboard";
            $item['href'] = url(\Helper::get_guard() . "/dashboard");
            return $item;
        }

        $user = Auth::user();
        $current_guard = Helper::get_guard();
        if ($user->hasRole('super-admin') && (isset($item['guard']) && in_array($current_guard, $item['guard']))) {
            return $item;
        } else if (isset($item['guard']) && in_array($current_guard, $item['guard'])) {
            if ($current_guard == "store") {
                $businessTypeId = $user->businessType->id;
                $menu = "menu_$businessTypeId";
                if (isset($item['type']) && in_array($menu, $item['type'])) {
                    if ((isset($item['exclude_service']) && $item['exclude_service'] == $user->service_type)) {
                        return false;
                    }
                    return $item;
                }
            } else if (isset($item['can'])) {
                if (is_array($item['can'])) {
                    if ($user->hasAnyPermission($item['can'])) {
                        return $item;
                    }
                } else {
                    if ($user->hasPermissionTo($item['can'])) {
                        return $item;
                    }
                }
            }
        }
        return false;
    }
}
