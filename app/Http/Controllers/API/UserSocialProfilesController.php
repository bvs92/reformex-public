<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserSocialProfilesController extends Controller
{
    public function get()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $social_profiles = $user->social_profiles ? $user->social_profiles : null;

        // $social_profiles = $social_profiles->map(function ($item) {
        //     $item[$item->type] = $item->username;
        //     return $item;
        // });

        $social_profiles = $social_profiles->pluck('username', 'type');

        return response()->json(['success' => true, 'social_profiles' => $social_profiles]);
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $validator = Validator::make($request->all(), [
            'facebook' => 'nullable|min:2|string',
            'instagram' => 'nullable|min:2|string',
            'youtube' => 'nullable|min:2|string',
            'twitter' => 'nullable|min:2|string',
            // 'tiktok' => 'nullable|min:2|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $facebook = \App\SocialProfile::where('user_id', auth()->user()->id)->where('type', 'facebook_profile')->first();

        if (!$facebook) {
            $facebook = new \App\SocialProfile();
            $facebook->user_id = auth()->user()->id;
            $facebook->type = 'facebook_profile';
        }

        $facebook->username = $valid_request['facebook'];

        if (!$facebook->save()) {
            return response()->json(['errors' => true]);
        }

        $instagram = \App\SocialProfile::where('user_id', auth()->user()->id)->where('type', 'instagram_profile')->first();

        if (!$instagram) {
            $instagram = new \App\SocialProfile();
            $instagram->user_id = auth()->user()->id;
            $instagram->type = 'instagram_profile';
        }

        $instagram->username = $valid_request['instagram'];
        if (!$instagram->save()) {
            return response()->json(['errors' => true]);
        }

        $youtube = \App\SocialProfile::where('user_id', auth()->user()->id)->where('type', 'youtube_profile')->first();

        if (!$youtube) {
            $youtube = new \App\SocialProfile();
            $youtube->user_id = auth()->user()->id;
            $youtube->type = 'youtube_profile';
        }

        $youtube->username = $valid_request['youtube'];
        if (!$youtube->save()) {
            return response()->json(['errors' => true]);
        }

        $twitter = \App\SocialProfile::where('user_id', auth()->user()->id)->where('type', 'twitter_profile')->first();

        if (!$twitter) {
            $twitter = new \App\SocialProfile();
            $twitter->user_id = auth()->user()->id;
            $twitter->type = 'twitter_profile';
        }

        $twitter->username = $valid_request['twitter'];
        if (!$twitter->save()) {
            return response()->json(['errors' => true]);
        }

        // $tiktok = \App\SocialProfile::where('user_id', auth()->user()->id)->where('type', 'tiktok_profile')->first();

        // if (!$tiktok) {
        //     $tiktok = new \App\SocialProfile();
        //     $tiktok->user_id = auth()->user()->id;
        //     $tiktok->type = 'tiktok_profile';
        // }

        // $tiktok->username = $valid_request['tiktok'] ? $valid_request['tiktok'] : '';
        // if (!$tiktok->save()) {
        //     return response()->json(['errors' => true]);
        // }

        $social_profiles = auth()->user()->social_profiles->pluck('username', 'type');
        return response()->json(['success' => true, 'social_profiles' => $social_profiles]);

    }
}
