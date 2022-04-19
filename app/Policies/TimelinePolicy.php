<?php

namespace App\Policies;

use App\Timeline;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimelinePolicy
{
    use HandlesAuthorization;


    // public function before($user, $ability)
    // {
    //     if ($user->isAdmin()) {
    //         return true;
    //     }
    // }

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
     * @param  \App\Timeline  $timeline
     * @return mixed
     */
    public function view(User $user, Timeline $timeline)
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
     * @param  \App\Timeline  $timeline
     * @return mixed
     */
    public function update(User $user, Timeline $timeline)
    {
        // dd($user->id === $timeline->user_id || $user->id === $timeline->professional_id);
        return $user->id === $timeline->user_id || $user->professional->id === $timeline->professional_id;
    }


    public function update_pro(User $user, Timeline $timeline)
    {
        return $user->professional->id === $timeline->professional_id;
    }

    public function update_client(User $user, Timeline $timeline)
    {
        // dd('hit');
        return $user->id === $timeline->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timeline  $timeline
     * @return mixed
     */
    public function delete(User $user, Timeline $timeline)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timeline  $timeline
     * @return mixed
     */
    public function restore(User $user, Timeline $timeline)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timeline  $timeline
     * @return mixed
     */
    public function forceDelete(User $user, Timeline $timeline)
    {
        //
    }
}
