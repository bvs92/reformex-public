<?php

namespace App\Policies;

use App\ClientMessage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientMessagePolicy
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
     * @param  \App\ClientMessage  $clientMessage
     * @return mixed
     */
    public function view(User $user, ClientMessage $clientMessage)
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
     * @param  \App\ClientMessage  $clientMessage
     * @return mixed
     */
    public function update(User $user, ClientMessage $clientMessage)
    {
        return $user->id === $clientMessage->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ClientMessage  $clientMessage
     * @return mixed
     */
    public function delete(User $user, ClientMessage $clientMessage)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ClientMessage  $clientMessage
     * @return mixed
     */
    public function restore(User $user, ClientMessage $clientMessage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ClientMessage  $clientMessage
     * @return mixed
     */
    public function forceDelete(User $user, ClientMessage $clientMessage)
    {
        //
    }
}
