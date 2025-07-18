<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\Auth\PasswordResetMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We can\'t find a user with that email address.',
        ]);

        try {
            // Find the user by login email
            $user = User::with('employee')->where('email', $request->email)->first();

            if (!$user || !$user->employee || !$user->employee->official_email) {
                return back()->withErrors([
                    'email' => 'No official email found for this user. Please contact administrator.',
                ]);
            }

            // Generate a unique token
            $token = Str::random(64);

            // Delete any existing password reset tokens for this user
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Create new password reset token
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            // Send password reset email to official email immediately (not queued for debugging)
            try {
                Mail::to($user->employee->official_email)->send(new PasswordResetMail($user, $token));
            } catch (Exception $mailException) {
                return back()->withErrors([
                    'email' => 'Failed to send email. Please contact administrator. Error: ' . $mailException->getMessage(),
                ]);
            }

            // Redirect to confirmation page
            return redirect()->route('password.sent')->with([
                'official_email' => $user->employee->official_email,
                'alias_name' => $user->employee->alias_name ?? $user->name,
            ]);

        } catch (Exception $e) {
            return back()->withErrors([
                'email' => 'An error occurred while sending the password reset link. Please try again. Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the password reset link sent confirmation page.
     */
    public function showLinkSentPage()
    {
        if (!session()->has('official_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.sent');
    }
}
