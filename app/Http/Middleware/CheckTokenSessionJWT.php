<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CheckTokenSessionJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check if token exists or not null, regenerate new one.

        if($request->user()){
            $cookie = Cookie::get('token_access');
    
            if(!$cookie || $cookie == null){
                $token = JWTAuth::fromUser($request->user());
    
                if($token) {
                    // $this->token = $token;
                    setcookie('token_access', $token);
                } 
            }
        }


        return $next($request);
    }
}
