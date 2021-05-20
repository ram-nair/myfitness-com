<?php

namespace App\Policies;

use App\Slot;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlotPolicy
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
        return $user->can('slot_read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Slot  $slot
     * @return mixed
     */
    public function view($user, Slot $slot)
    {
        return $user->can('slot_read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->can('slot_create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Slot  $slot
     * @return mixed
     */
    public function update($user, Slot $slot)
    {
        return $user->can('slot_update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Slot  $slot
     * @return mixed
     */
    public function delete($user, Slot $slot)
    {
        return $user->can('slot_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Slot  $slot
     * @return mixed
     */
    public function restore($user, Slot $slot)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Slot  $slot
     * @return mixed
     */
    public function forceDelete($user, Slot $slot)
    {
        //
    }
}
