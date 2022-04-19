<?php

namespace App\Policies;

use App\SocialProfile;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialProfilePolicy
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
     * @param  \App\SocialProfile  $socialProfile
     * @return mixed
     */
    public function view(User $user, SocialProfile $socialProfile)
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
     * @param  \App\SocialProfile  $socialProfile
     * @return mixed
     */
    public function update(User $user, SocialProfile $socialProfile)
    {
        return $user->id === $socialProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SocialProfile  $socialProfile
     * @return mixed
     */
    public function delete(User $user, SocialProfile $socialProfile)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\SocialProfile  $socialProfile
     * @return mixed
     */
    public function restore(User $user, SocialProfile $socialProfile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SocialProfile  $socialProfile
     * @return mixed
     */
    public function forceDelete(User $user, SocialProfile $socialProfile)
    {
        //
    }
}
