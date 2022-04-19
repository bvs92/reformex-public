<?php

namespace App\Policies;

use App\Prospect;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProspectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Prospect  $prospect
     * @return mixed
     */
    public function view(User $user, Prospect $prospect)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Prospect  $prospect
     * @return mixed
     */
    public function update(User $user, Prospect $prospect)
    {
        return $user->id === $prospect->user_id;
    }


    public function update_pro(User $user, Prospect $prospect)
    {
        return $user->professional->id === $prospect->professional_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Prospect  $prospect
     * @return mixed
     */
    public function delete(User $user, Prospect $prospect)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Prospect  $prospect
     * @return mixed
     */
    public function restore(User $user, Prospect $prospect)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Prospect  $prospect
     * @return mixed
     */
    public function forceDelete(User $user, Prospect $prospect)
    {
        //
    }
}
