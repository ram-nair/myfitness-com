<?php

namespace App\Policies;

use App\StoreProduct;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreProductPolicy
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
        return $user->can('storeproduct_read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreProduct  $storeProduct
     * @return mixed
     */
    public function view($user, StoreProduct $storeProduct)
    {
        return $user->can('storeproduct_read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->can('storeproduct_create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreProduct  $storeProduct
     * @return mixed
     */
    public function update($user, StoreProduct $storeProduct)
    {
        return $user->can('storeproduct_update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreProduct  $storeProduct
     * @return mixed
     */
    public function delete($user, StoreProduct $storeProduct)
    {
        return $user->can('storeproduct_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreProduct  $storeProduct
     * @return mixed
     */
    public function restore($user, StoreProduct $storeProduct)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreProduct  $storeProduct
     * @return mixed
     */
    public function forceDelete($user, StoreProduct $storeProduct)
    {
        //
    }
}
