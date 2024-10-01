<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login field to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'userid';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'userid' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Override credentials method to prepend 'UID' to userid.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'userid' => 'UID' . $request->input('userid'), // Retrieve the input and prepend 'UID' to the userid field
            'password' => $request->input('password'),
        ];
    }

}
