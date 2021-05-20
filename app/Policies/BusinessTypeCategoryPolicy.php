<?php

namespace App\Policies;

use App\BusinessTypeCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessTypeCategoryPolicy
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
        return $user->can('bussinesstypecategory_read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessTypeCategory  $businessTypeCategory
     * @return mixed
     */
    public function view($user, BusinessTypeCategory $businessTypeCategory)
    {
        return $user->can('bussinesstypecategory_read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->can('bussinesstypecategory_create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessTypeCategory  $businessTypeCategory
     * @return mixed
     */
    public function update($user, BusinessTypeCategory $businessTypeCategory)
    {
        return $user->can('bussinesstypecategory_update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessTypeCategory  $businessTypeCategory
     * @return mixed
     */
    public function delete($user, BusinessTypeCategory $businessTypeCategory)
    {
        return $user->can('bussinesstypecategory_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessTypeCategory  $businessTypeCategory
     * @return mixed
     */
    public function restore($user, BusinessTypeCategory $businessTypeCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BusinessTypeCategory  $businessTypeCategory
     * @return mixed
     */
    public function forceDelete($user, BusinessTypeCategory $businessTypeCategory)
    {
        //
    }
}
