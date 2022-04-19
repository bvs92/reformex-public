<?php

namespace App\Http\Controllers;

class CompaniesController extends Controller
{

    /**
     * Display a listing of the pending pro registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.companies.pending');
    }

    public function details($id)
    {
        $user = \App\User::findOrFail($id);

        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        $user->roles;
        $user->makeHidden(['password']);
        $user['is_pro'] = $user->isPro();
        $user->registration;

        return view('volgh.companies.details', compact('user'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back();
        }

        return view('volgh.companies.create');
    }
}
