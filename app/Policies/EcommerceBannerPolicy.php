<?php

namespace App\Policies;

use App\EcommerceBanner;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EcommerceBannerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny($user)
    {
        return $user->can('categorybanner_read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\CategoryBanner  $categoryBanner
     * @return mixed
     */
    public function view($user, EcommerceBanner $ecommerceBanner)
    {
        return $user->can('categorybanner_read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->can('categorybanner_create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\CategoryBanner  $categoryBanner
     * @return mixed
     */
    public function update($user, EcommerceBanner $ecommerceBanner)
    {
        return $user->can('categorybanner_update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\CategoryBanner  $categoryBanner
     * @return mixed
     */
    public function delete($user, EcommerceBanner $ecommerceBanner)
    {
        return $user->can('categorybanner_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\CategoryBanner  $categoryBanner
     * @return mixed
     */
    public function restore($user, EcommerceBanner $ecommerceBanner)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\CategoryBanner  $categoryBanner
     * @return mixed
     */
    public function forceDelete($user, EcommerceBanner $ecommerceBanner)
    {
        //
    }
}
