<?php

namespace App\Http\Controllers;

use App\User;
use App\SocialProfile;
use Illuminate\Http\Request;

class SocialProfileController extends Controller
{
    

    public function saveSocialProfiles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'facebook_profile'  => 'nullable',
            'instagram_profile'  => 'nullable',
            'youtube_profile'  => 'nullable',
            'twitter_profile'  => 'nullable',
            'tiktok_profile'  => 'nullable',
        ]);



        if($user->social_profiles){


            foreach($validated as $key => $profile){

                if($user->social_profiles->contains('type', $key)){
                    $the_profile = $user->social_profiles()->where('type', $key)->first();
                    $the_profile->username = $profile;
                    $the_profile->save();
                } else {
                    if($profile !== null){
                        $social_profile = new SocialProfile();
                        $social_profile->user_id = $user->id;
                        $social_profile->username = $profile;
                        $social_profile->type = $key;
                        $social_profile->save();
                    }
                }
            }

        } else {
            foreach($validated as $key => $profile){
                if($profile !== null){
                    $social_profile = new SocialProfile();
                    $social_profile->user_id = $user->id;
                    $social_profile->username = $profile;
                    $social_profile->type = $key;
                    $social_profile->save();
                }
            }
        }

        return redirect()->back();


        // foreach($validated as $key => $profile){

        //     if($user->social_profiles){

        //         foreach($user->social_profiles as $key_e => $single_profile){

        //             if($key_e == $key){
        //                 $single_profile->username = $profile;
        //                 $single_profile->save();
        //             }
        //         }

        //     } else {
        //         if($profile !== null){
        //             $social_profile = new SocialProfile();
        //             $social_profile->user_id = $user->id;
        //             $social_profile->username = $profile;
        //             $social_profile->type = $key;
        //             $social_profile->save();
        //         }
        //     }
        // }

        // dd($validated);

    }

}
