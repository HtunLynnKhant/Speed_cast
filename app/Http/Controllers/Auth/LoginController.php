<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show login view.
     *
     * @return View|RedirectResponse
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            // Redirect based on user role
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'You are already logged in as Admin!');
            }

            if (Auth::user()->hasRole('superadmin')) {
                return redirect()->route('superadmin.dashboard')->with('success', 'You are already logged in as SuperAdmin!');
            }
        }

        // Show login view if the user is not authenticated
        return view('admin.Auth.login');
    }

    /**
     * Logging in.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log in
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if (!$user->remember_token) {
                $user->remember_token = Str::random(60);
                $user->save();
            }
            // Check user role and redirect accordingly
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with([
                    'success' => 'You are logged in as Admin!',
                    'loginSuccess' => true,
                ]);
            }

            if ($user->hasRole('superadmin')) {
                return redirect()->route('superadmin.dashboard')->with([
                    'success' => 'You are logged in as SuperAdmin!',
                    'loginSuccess' => true,
                ]);
            }

            if ($user->hasRole('client')) {
                return redirect()->route('client.dashboard')->with([
                    'success' => 'You are logged in as client!',
                    'loginSuccess' => true,
                ]);
            }

            if ($user->hasRole('driver')) {
                return redirect()->route('driver.dashboard')->with([
                    'success' => 'You are logged in as driver!',
                    'loginSuccess' => true,
                ]);
            }
        }

        // If authentication fails, redirect back to login with an error
        return redirect()->route('login')->with('error', 'Invalid email or password!');
    }

    /**
     * Logging the user out.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        // Redirect to the appropriate login page based on user role
        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
    /**
     * Logging the user out.
     *
     * @return RedirectResponse
     */
    public function sadminlogout(): RedirectResponse
    {
        Auth::logout();

        // Redirect to the appropriate login page based on user role
        return redirect('superadmin/')->with('status', 'You have been logged out successfully.');
    }

    public function clientogout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('clientlogin')->with('status', 'You have been logged out successfully.');
    }
}
