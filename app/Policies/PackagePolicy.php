<?php

namespace App\Policies;

use App\Package;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
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
        return $user->can('package_read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Package  $Package
     * @return mixed
     */
    public function view($user, Package $Package)
    {
        return $user->can('package_read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->can('package_read');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Package  $Package
     * @return mixed
     */
    public function update($user, Package $Package)
    {
        return $user->can('package_update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Package  $Package
     * @return mixed
     */
    public function delete($user, Package $Package)
    {
        return $user->can('package_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Package  $Package
     * @return mixed
     */
    public function restore($user, Package $Package)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Package  $Package
     * @return mixed
     */
    public function forceDelete($user, Package $Package)
    {
        //
    }
}
