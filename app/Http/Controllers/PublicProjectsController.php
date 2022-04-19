<?php

namespace App\Http\Controllers;

class PublicProjectsController extends Controller
{
    public function get($username, $uuid)
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

        $project = \App\WorkProject::where('uuid', $uuid)->firstOrFail();
        // return $project;

        $user = $project->user;

        $projects = $user->projects;
        $public_profile = $user->public_profile;
        return view('volgh.public.project', [
            'user' => $user,
            'projects' => $projects,
            'single_project' => $project,
            'public_profile' => $public_profile,
        ]);
    }
}
