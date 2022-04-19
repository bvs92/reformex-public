<?php

namespace App\Http\Controllers;

use App\Badge;
use App\Credit;
use App\Professional;
use App\Registration;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/start';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register_pro()
    {
        return view('auth.register-company');
    }

    public function store_pro(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'api_token' => Str::random(60),
        ]);

        $username = $this->generateUsername($validated['first_name'], $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => '0',
        ]);

        $user->assignRole('professional');

        Professional::create([
            'user_id' => $user->id,
        ]);

        // Creare profil Credit
        if (!$user->credit) {
            Credit::create([
                'user_id' => $user->id,
                'amount' => 0,
            ]);
        }

        // create a registration.
        Registration::create([
            'user_id' => $user->id,
            'status' => 0,
        ]);

        if (!$user->badge) {
            Badge::create([
                'user_id' => $user->id,
            ]);
        }

        // send email for verification
        event(new Registered($user));

        if (!Auth::check()) {
            // auth the user
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                // Authentication passed...
                // verify email event

                return redirect()->intended('start');
            }
        }

        return redirect()->route('home');
    }

    private function generateUsername($firstname, $lastname)
    {
        $last_name = \Illuminate\Support\Str::slug($lastname, '_');
        $first_name = \Illuminate\Support\Str::slug($firstname, '_');
        $username = $last_name . '_' . $first_name . '_' . time();

        while (\App\User::where('username', $username)->get()->count() > 0) {
            // regenereaza cu un alt timestamp
            $username = $last_name . '_' . $first_name . '_' . time();
        }

        return $username;
    }
}
