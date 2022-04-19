<?php

namespace App\Http\Controllers;

class PublicProfessionalController extends Controller
{

    public function profile($username)
    {

        $user_name = \App\Username::where('username', $username)->first();

        if ($user_name) {
            $user = $user_name->user;
        } else {
            $user = \App\User::where('username', $username)->first();
        }

        if (!$user->isPro()) {
            abort(404);
        }

        $projects = $user->projects;
        $categories = $user->professional->categories;
        $judete = $user->judets;
        $public_profile = $user->public_profile;
        return view('volgh.public.profile', [
            'user' => $user,
            'projects' => $projects,
            'categories' => $categories,
            'judete' => $judete,
            'public_profile' => $public_profile,
        ]);
    }
}
