<?php

namespace App\Http\Controllers;

use App\Post;
// use App\Mail\ContactMe;
use App\User;
use Illuminate\Http\Request;
use App\Notifications\ContactMe;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    
    public function show()
    {
        return view('emails.contact');
    }

    public function store()
    {
        $validated = request()->validate([
            'email' => 'required|email'
        ]);

        // $post = Post::firstOrFail();

        $user = User::first();

        Notification::send($user, new ContactMe("Hello there"));

        // Mail::to($validated['email'])->send(new ContactMe($post));
        
        return redirect()->route('emails.contact')->with('success', 'Email sent.');
    }
}
