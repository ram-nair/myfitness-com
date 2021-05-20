<?php

namespace App\Policies;

use App\ServiceBanner;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceBannerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny($user, $type)
    {
        return $user->can("servicestype{$type}banner_read");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceBanner  $serviceBanner
     * @return mixed
     */
    public function view($user, ServiceBanner $serviceBanner, $type)
    {
        return $user->can("servicestype{$type}banner_read");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user, $type)
    {
        return $user->can("servicestype{$type}banner_create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceBanner  $serviceBanner
     * @return mixed
     */
    public function update($user, ServiceBanner $serviceBanner, $type)
    {
        return $user->can("servicestype{$type}banner_update");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceBanner  $serviceBanner
     * @return mixed
     */
    public function delete($user, ServiceBanner $serviceBanner)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceBanner  $serviceBanner
     * @return mixed
     */
    public function restore($user, ServiceBanner $serviceBanner)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceBanner  $serviceBanner
     * @return mixed
     */
    public function forceDelete($user, ServiceBanner $serviceBanner)
    {
        //
    }
}
