<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SuperAdminLoginController extends Controller
{
    /**
     * Show login view.
     *
     * @return View|RedirectResponse
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->role === 'superadmin') {
            // Redirect to the superadmin dashboard if the user is already logged in as superadmin
            return redirect()->route('superadmin.dashboard')->with('success', 'You are already logged in!');
        }

        // Show the login view if the user is not authenticated
        return view('superadmin.Auth.login'); // Ensure this view path exists
    }

    /**
     * Logging in.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postLogin(Request $request): RedirectResponse
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Retrieve the credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Check if the user is a superadmin
            if (Auth::user()->role === 'superadmin') {
                // Redirect to the superadmin dashboard with a success message
                return redirect()->route('superadmin.dashboard')->with([
                    'success' => 'You are logged in as SuperAdmin!',
                    'showModal' => true,
                ]);
            } else {
                // Log out the user if they do not have superadmin access
                Auth::logout();

                // Redirect back to the login page with an error message
                return redirect()->route('superadminlogin')->with('error', 'You do not have superadmin access.');
            }
        }

        // If authentication fails, redirect back with an error message
        return redirect()->route('superadminlogin')->with('error', 'Invalid email or password!');
    }

    /**
     * Logging the user out.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('superadmin.showLogin')->with('status', 'You have been logged out successfully.');
    }
}
