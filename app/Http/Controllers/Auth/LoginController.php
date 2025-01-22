<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
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
    public function loginEmail()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'The Login Email is required.',
            'password.required' => 'The password is required.',
        ]);
    }


    /**
     * Email & password
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }

    /**
     * Override the attemptLogin method to check for active status
     */
    protected function attemptLogin(Request $request)
    {
        // Add status check here
        return $this->guard()->attempt(
            array_merge($this->credentials($request), ['status' => 'Active']),
            $request->filled('remember')
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user && $user->status !== 'Active') {
            return redirect()->back()->withErrors([
                'email' => 'Your account is not active. Please contact support.',
            ]);
        }

        throw ValidationException::withMessages([
            $this->loginEmail() => [trans('auth.failed')],
        ]);
    }

}
