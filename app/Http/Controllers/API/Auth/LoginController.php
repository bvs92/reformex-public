<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials.']);
        }

        // este corect?
        // if(Cookie::get('token_access') !== null){
        //     return response()->json(['exista_cookie' => $token])->cookie(Cookie::get('token_access'));
        // } else {
        //     $cookie = cookie('token_access', $token, env('JWT_TTL'));
        // }

        $cookie = cookie('token_access', $token, env('JWT_TTL'));

        return response()->json(['token' => $token])->cookie($cookie);
    }

    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['token' => $newToken]);
    }

    // public function relogin()
    // {

    //     try {
    //         $user = auth()->user();
    //     } catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
    //         return response()->json(['error' => $e->getMessage()]);
    //     }

    //     $token = JWTAuth::fromUser($user);
    //     if($token) {
    //         // $this->token = $token;
    //         setcookie('token_access', $token);
    //     } else {
    //         setcookie('token_access', null);
    //     }
    // }

    public function logout()
    {
        # invalidate the token.
        # execute this function before the LOGOUT function itself.
        try {
            // $result = auth()->invalidate();
            $result = Auth::guard('api')->logout();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['result' => $result]);

    }
}
