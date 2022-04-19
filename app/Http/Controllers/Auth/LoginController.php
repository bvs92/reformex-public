<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\Request;
use Illuminate\Support\Facades\Cookie;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    private $token;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // dd($user->only(['email', 'password']));
        // try {
        //     // attempt to verify the credentials and create a token for the user
        //     if (! $token = JWTAuth::attempt($user->only(['email', 'password']))) {
        //         return response()->json(['error' => 'invalid_credentials'], 401);
        //     }
        // } catch (JWTException $e) {
        //     // something went wrong whilst attempting to encode the token
        //     return response()->json(['error' => 'could_not_create_token'], 500);
        // }

        $token = JWTAuth::fromUser($user);
        if($token) {
            // $this->token = $token;
            setcookie('token_access', $token);
        } else {
            setcookie('token_access', null);
        }

        // setcookie('ce_dracu_e_asta', time());
    }


    protected function loggedOut(\Illuminate\Http\Request $request)
    {
        setcookie('token_access', null, time() - 3600);
        // setcookie('ce_dracu_e_asta', null, time() - 3600);

        // if(Auth::guard('api')->user()){
        //     Auth::guard('api')->logout();
        // }

        // if(Auth::guard('api')->user()){
        //     // Auth::guard('api')->logout();
        //     $token = JWTAuth::fromUser(Auth::guard('api')->user());
        //     JwtAuth::refresh($token);
        // }

        

    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(\Illuminate\Http\Request $request)
    {

        // Auth::guard('api')->logout();

        // $token = JWTAuth::fromUser(Auth::guard('api')->user());
        // JwtAuth::refresh($token);
        
        // JWTAuth::invalidate(Auth::guard('api')->getToken());
        

        // invalidate JWT token before logout.
        // $token = JWTAuth::fromUser(auth()->user());
        // $token = Cookie::get('token_access');
        // try {
        //     // $token = "Bearer " . $request->cookie('access_token');
        //     // JWTAuth::invalidate($token);
        //     JWTAuth::invalidate($this->token);
        // } catch (JWTException $e) {
        //     return response();
        // }
        // JWTAuth::invalidate(JWTAuth::getToken());

        // $new_token = JWTAuth::refresh($token);
        // JWTAuth::setToken($new_token);

        // $token = "Bearer " + $request->cookie('access_token');
        // try {
        //     auth()->setToken($token)->getPayload();
        // } catch (\Exception $e) {
        //     return response()->json(['message'=>'Unauthorized'], 401);
        // }

        $this->guard()->logout();

        // if(Auth::guard('api')->user()){
        //     // Auth::guard('api')->logout();
        //     $token = JWTAuth::fromUser(Auth::guard('api')->user());
        //     JwtAuth::refresh($token);
        // }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // setcookie('token_access', null, time() - 3600);

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }


}
