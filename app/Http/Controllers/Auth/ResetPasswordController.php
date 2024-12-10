<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Show the form to reset the password.
     *
     * @param string|null $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        // dd($request->all(), $token);
        // Retrieve the reset record
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Check if the record exists and the token matches
        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            alert('Error!', 'Invalid or expired password reset token.', 'error');
            return redirect()->route('login');
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle a password reset request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            // Retrieve the reset record
            $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            // Check if the record exists and the token matches
            if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
                alert('Error!', 'Invalid or expired reset token.', 'error');
                return redirect()->back()->withInput();
            }

            // Update the user's password
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            // Delete the password reset record
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Redirect with success message
            toast('Password Reset Successfully.', 'success');
            return redirect()->route('login');
        } catch (Exception $e) {
            // Handle unexpected errors
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
