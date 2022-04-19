<?php

namespace App\Http\Middleware;

use Closure;

class CheckServer
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
        $authorization_token = $request->header('x-api-key');
        // return response()->json([$request->header()]);
        // $referer = parse_url(request()->headers->get('referer'), PHP_URL_HOST);
        // $server = request()->getHttpHost();

        // if ($referer == 'localhost' || $referer == 'reformexapp.eu' || $referer == 'reformex.ro' || $referer == '127.0.0.1:8000') {
        //     return $next($request);
        // }

        // if ($server == '127.0.0.1:8000' || $server == 'localhost') {
        //     return $next($request);
        // }

        // return response()->json($authorization_token);

        // get the tokens from DB
        $token = \App\ApiKey::where('key', $authorization_token)->first();

        // return response()->json($token);

        if (!$token) {
            return response()->json(['error' => 'Acces denied.']);
        }

        // if ($referer != 'reformex-listare.herokuapp.com') {
        //     return response()->json(['error' => 'Acces denied.']);
        // }
        // dd('aici');
        return $next($request);

    }
}
