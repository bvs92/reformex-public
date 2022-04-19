<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicUserProfile extends Controller
{
    public function getDescription()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = auth()->user();

        if (!$user->public_profile) {
            return response()->json(['description' => null]);
        } else {
            return response()->json(['description' => $user->public_profile->description]);
        }

    }

    public function saveUserDescription(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if (empty($request->description)) {
            return response()->json(['errors' => true]);
        }

        if (!$user->public_profile) {
            $profile = new \App\UserPublicProfile;
            $profile->user_id = $user->id;
            $profile->description = $request->description;
            if (!$profile->save()) {
                return response()->json(['errors' => true]);
            }
            return response()->json(['success' => true, 'description' => $profile->description]);
        } else {
            $user->public_profile->description = $request->description;
            if (!$user->public_profile->save()) {
                return response()->json(['errors' => true]);
            }
            return response()->json(['success' => true, 'description' => $user->public_profile->description]);
        }
    }

    public function getWebsite()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $user = auth()->user();

        if (!$user->public_profile) {
            return response()->json(['website' => null]);
        } else {
            return response()->json(['website' => $user->public_profile->website]);
        }

    }

    public function saveUserWebsite(Request $request)
    {

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        if (empty($request->website)) {
            return response()->json(['errors' => true]);
        }

        if (!$user->public_profile) {
            $profile = new \App\UserPublicProfile;
            $profile->user_id = $user->id;
            $profile->description = '';
            $profile->website = $request->website;
            if (!$profile->save()) {
                return response()->json(['errors' => true]);
            }
            return response()->json(['success' => true, 'website' => $profile->website]);
        } else {
            $user->public_profile->website = $request->website;
            if (!$user->public_profile->save()) {
                return response()->json(['errors' => true]);
            }
            return response()->json(['success' => true, 'website' => $user->public_profile->website]);
        }
    }
}
