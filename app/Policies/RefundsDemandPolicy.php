<?php

namespace App\Policies;

use App\RefundsDemand;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefundsDemandPolicy
{
    use HandlesAuthorization;


    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

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
     * @param  \App\RefundsDemand  $refundsDemand
     * @return mixed
     */
    public function view(User $user, RefundsDemand $refundsDemand)
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
     * @param  \App\RefundsDemand  $refundsDemand
     * @return mixed
     */
    public function update(User $user, RefundsDemand $refundsDemand)
    {
        return $user->id === $refundsDemand->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\RefundsDemand  $refundsDemand
     * @return mixed
     */
    public function delete(User $user, RefundsDemand $refundsDemand)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\RefundsDemand  $refundsDemand
     * @return mixed
     */
    public function restore(User $user, RefundsDemand $refundsDemand)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\RefundsDemand  $refundsDemand
     * @return mixed
     */
    public function forceDelete(User $user, RefundsDemand $refundsDemand)
    {
        //
    }
}
