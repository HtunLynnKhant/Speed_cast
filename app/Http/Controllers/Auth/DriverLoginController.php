<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class DriverLoginController extends Controller
{
    /**
     * Show login view.
     *
     * @return View|RedirectResponse
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->role === 'driver') {
            // Redirect to the superadmin dashboard if the user is already logged in as superadmin
            return redirect()->route('driver.dashboard')->with('success', 'You are already logged in!');
        }

        // Show the login view if the user is not authenticated
        return view('Driver.Auth.login'); // Ensure this view path exists
    }
    /**
     * Logging in.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('driver.dashboard');
        }

        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        // Retrieve the credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->remember_token) {
                $user->remember_token = Str::random(60);
                $user->save();
            }
            // Check if the user is a client and if their password needs to be changed
            if ($user->role === 'driver') {
                // If the password has not been changed, set session variable
                if ($user->password_changed === 0) {
                    session(['showDpasswordModal' => true]);  // Set session variable to show modal

                    return redirect()->route('driver.dashboard');
                }

                // If password was already changed, proceed normally
                return redirect()->route('driver.dashboard')->with('success', 'You are logged in as Driver!');
            } else {
                // If the user is not a client, log them out
                Auth::logout();

                return redirect()->route('driverlogin')->with('error', 'You do not have Driver access.');
            }
        }

        // If authentication fails, redirect back with an error message
        return redirect()->route('driverlogin')->with('error', 'Invalid email or password!');
    }

    /**
     * Logging the user out.
     *
     * @return RedirectResponse
     */
    public function driverlogout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('driverlogin')->with('status', 'You have been logged out successfully.');
    }

    // Controller method
    public function updatePassword(Request $request)
    {
        // Validate the incoming password and confirmation fields
        $request->validate([
            'password' => 'required|confirmed|min:5',  // password must match confirmation and meet the min length
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);  // bcrypt to hash the password
        $user->password_changed = 1;  // Mark the password as changed
        $user->save();  // Save the user data with the updated password

        session()->forget('showDpasswordModal'); // Clear session variable after password change

        // Redirect back with a success message
        return redirect()->route('driver.dashboard')->with('success', 'Your password has been updated!');
    }
}
