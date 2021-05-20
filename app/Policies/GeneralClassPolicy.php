<?php

namespace App\Policies;

use App\GeneralClass;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeneralClassPolicy
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
        return $user->can("{$type}class_read");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\OnlineClass  $onlineClass
     * @return mixed
     */
    public function view($user, GeneralClass $generalClass, $type)
    {
        return $user->can("{$type}class_read");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user, $type)
    {
        return $user->can("{$type}class_create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\OnlineClass  $onlineClass
     * @return mixed
     */
    public function update($user, GeneralClass $generalclass, $type)
    {
        return $user->can("{$type}class_update");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OnlineClass  $onlineClass
     * @return mixed
     */
    public function delete($user, GeneralClass $generalclass, $type)
    {
        return $user->can("{$type}class_delete");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\OnlineClass  $onlineClass
     * @return mixed
     */
    public function restore($user, GeneralClass $generalclass)
    {
        // 
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OnlineClass  $onlineClass
     * @return mixed
     */
    public function forceDelete($user, GeneralClass $generalclass)
    {
        // 
    }
}
