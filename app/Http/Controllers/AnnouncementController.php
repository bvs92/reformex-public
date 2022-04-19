<?php

namespace App\Http\Controllers;

class AnnouncementController extends Controller
{
    public function index()
    {

        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with(['error' => 'Nu aveti permisiunea de a accesa sectiunea.']);
        }

        return view('volgh.announcements.index');
    }
}
