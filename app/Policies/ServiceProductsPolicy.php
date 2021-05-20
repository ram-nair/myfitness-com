<?php

namespace App\Policies;

use App\ServiceProducts;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceProductsPolicy
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
        return $user->can("servicetype{$type}product_read");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProducts  $serviceProducts
     * @return mixed
     */
    public function view($user, ServiceProducts $serviceProducts, $type)
    {
        return $user->can("servicetype{$type}product_read");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user, $type)
    {
        return $user->can("servicetype{$type}product_create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProducts  $serviceProducts
     * @return mixed
     */
    public function update($user, ServiceProducts $serviceProducts, $type)
    {
        return $user->can("servicetype{$type}product_update");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProducts  $serviceProducts
     * @return mixed
     */
    public function delete($user, ServiceProducts $serviceProducts, $type)
    {
        return $user->can("servicetype{$type}product_delete");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProducts  $serviceProducts
     * @return mixed
     */
    public function restore($user, ServiceProducts $serviceProducts)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceProducts  $serviceProducts
     * @return mixed
     */
    public function forceDelete($user, ServiceProducts $serviceProducts)
    {
        //
    }
}
