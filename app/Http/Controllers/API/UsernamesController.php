<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsernamesController extends Controller
{

    public function check(Request $request)
    {
        // return $request->all();

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $usernames = \App\Username::where('username', $request->username)->get();

        if ($user->user_name_profile) {
            $current_user_name = $user->user_name_profile->username;

            if ($current_user_name) {
                $usernames = $usernames->filter(function ($item) use ($current_user_name) {
                    if ($item->username != $current_user_name) {
                        return $item;
                    }
                });
            }
        }

        // return $usernames;

        if ($usernames->count() > 0) {
            return response()->json(['errors' => true]);
        }

        return response()->json(['success' => true]);

    }

    public function set(Request $request)
    {
        // return $request->all();

        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $request->username = Str::slug($request->username, '-');
        strtolower($request->username);

        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3|max:255|unique:usernames,username',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()], 401);
        }

        $validated = $validator->validated();

        if ($user->user_name_profile) {
            $user->user_name_profile->username = strtolower($validated['username']);

            if (!$user->user_name_profile->save()) {
                return response()->json(['errors' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
            }
        } else {
            $username_profile = new \App\Username;
            $username_profile->username = strtolower($validated['username']);
            $username_profile->user_id = $user->id;
            if (!$username_profile->save()) {
                return response()->json(['errors' => 'Am intampinat erori. Va rugam incercati mai tarziu.']);
            }
        }

        return response()->json(['success' => true]);
    }
}
