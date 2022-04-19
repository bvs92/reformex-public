<?php

namespace App\Observers;

use App\Profile;

class ProfileObserver
{
    /**
     * Handle the profile "created" event.
     *
     * @param  \App\Profile  $profile
     * @return void
     */
    public function created(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "updated" event.
     *
     * @param  \App\Profile  $profile
     * @return void
     */
    public function updated(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "deleted" event.
     *
     * @param  \App\Profile  $profile
     * @return void
     */
    public function deleted(Profile $profile)
    {
        if($profile){

            if($profile->profile_photo == "default-photo.png"){
                $profile->delete();
                return redirect()->back()->with('error', 'Eroare. Nu exista avatar.');
                
            } else {
                if(!unlink(public_path('images/avatars/' . $profile->profile_photo)))
                    return redirect()->back()->with('error', 'Eroare. Va rugam sa incercati mai tarziu.');
    
                if(public_path('images/avatars/thumbnails/' . $profile->profile_photo) !== null){
                    unlink(public_path('images/avatars/thumbnails/' . $profile->profile_photo));
                }
               
                $profile->delete();
                return redirect()->route('user.profile')->with('success', 'Modificare efectuata cu succes.');   
            }
        } else {
            return redirect()->back()->with('error', 'Eroare. Nu exista avatar.'); 
        }
    }

    /**
     * Handle the profile "restored" event.
     *
     * @param  \App\Profile  $profile
     * @return void
     */
    public function restored(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "force deleted" event.
     *
     * @param  \App\Profile  $profile
     * @return void
     */
    public function forceDeleted(Profile $profile)
    {
        //
    }
}
