<?php

namespace App\Http\Controllers;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('home');
        }

        if (!auth()->user()->isPro()) {
            return redirect()->route('home');
        }

        return view('volgh.notifications.settings');
    }
}
