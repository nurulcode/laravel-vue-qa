<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

    public function Login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $login = [
            $loginType => $request->username,
            'password' => $request->password
        ];

        if (auth()->attempt($login)) {
            return redirect()->route('home');
        }
        return redirect()->route('login')->with('error', 'Incorrect email or password');
    }

    public function getToken(Request $request)
    {
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => 2,
            'client_secret' => 'VMwBeHA7jYAJHppicZePvmmHTJAufiUS6cNvBHy4',
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $requestToken = Request::create(env('APP_URL'). '/oauth/token', 'post');
        $response = Route::dispatch($requestToken);
        return $response;
    }
}
