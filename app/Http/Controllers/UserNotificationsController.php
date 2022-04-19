<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    

    public function index()
    {
        return view('notifications.index', [
            // 'notifications' => \DB::table('notifications')->get()
            'notifications' => \App\User::findOrFail(2)->notifications
        ]);
    }
}
