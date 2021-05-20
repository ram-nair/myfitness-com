<?php

namespace App\Policies;

use App\StoreServiceProducts;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreServiceProductsPolicy
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
        return $user->can("servicestype{$type}storeproduct_read");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreServiceProducts  $storeServiceProducts
     * @return mixed
     */
    public function view($user, StoreServiceProducts $storeServiceProducts, $type)
    {
        return $user->can("servicestype{$type}storeproduct_read");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreServiceProducts  $storeServiceProducts
     * @return mixed
     */
    public function update($user, StoreServiceProducts $storeServiceProducts, $type)
    {
        return $user->can("servicestype{$type}storeproduct_update");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreServiceProducts  $storeServiceProducts
     * @return mixed
     */
    public function delete($user, StoreServiceProducts $storeServiceProducts, $type)
    {
        return $user->can("servicestype{$type}storeproduct_delete");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreServiceProducts  $storeServiceProducts
     * @return mixed
     */
    public function restore($user, StoreServiceProducts $storeServiceProducts)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\StoreServiceProducts  $storeServiceProducts
     * @return mixed
     */
    public function forceDelete(User $user, StoreServiceProducts $storeServiceProducts)
    {
        //
    }
}
