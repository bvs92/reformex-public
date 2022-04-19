<?php

namespace App\Http\Middleware;

use Closure;

class RolesExists
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
        // dd(auth()->user()->roles->count());
        if(auth()->user()->roles->count() < 1){
            return redirect()->route('home');
        }

        return $next($request);
    }
}
