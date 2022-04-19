<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserStatus
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
        if(!auth()->user()->isActive()){
            
            return redirect()->route('users.inactive');
            // return view('volgh.users.inactive');
        }
        // dd('aici');
        return $next($request);

    }
}
