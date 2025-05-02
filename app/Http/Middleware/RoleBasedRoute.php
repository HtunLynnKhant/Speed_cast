<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoleBasedRoute
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            Session::flash('loginerror', 'You must log in to access!');

            // Redirect unauthenticated users based on the route they are trying to access
            if ($request->is('superadmin/*')) {
                return redirect('superadmin/');
            }

            if ($request->is('admin/*')) {
                return redirect('admin/');
            }

            if ($request->is('client/*')) {
                return redirect('client/');
            }

            if ($request->is('driver/*')) {
                return redirect('driver/');
            }

            return redirect('/auth/login');
        }

        // Check for superadmin role
        if ($user->hasRole('superadmin')) {
            // Deny access to admin routes for superadmin
            if ($request->is('admin/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('superadmin.dashboard');
            }
            // Deny access to client routes for superadmin
            if ($request->is('client/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('superadmin.dashboard');
            }

            if ($request->is('driver/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('driver.dashboard');
            }
        }

        // Check for admin role
        if ($user->hasRole('admin')) {
            // Deny access to superadmin routes for admin
            if ($request->is('superadmin/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('admin.dashboard');
            }
            // Deny access to client routes for admin
            if ($request->is('client/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('admin.dashboard');
            }

            if ($request->is('driver/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('admin.dashboard');
            }
        }

        // Check for client role
        if ($user->hasRole('client')) {
            // Deny access to superadmin and admin routes for client
            if ($request->is('superadmin/*') || $request->is('admin/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('client.dashboard');
            }
        }

        if ($user->hasRole('driver')) {
            // Deny access to superadmin routes for admin
            if ($request->is('superadmin/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('admin.dashboard');
            }
            // Deny access to client routes for admin
            if ($request->is('client/*')) {
                Session::flash('loginerror', 'You do not have permission to access this route.');

                return redirect()->route('admin.dashboard');
            }
        }

        // Proceed if role matches the request route
        return $next($request);
    }
}
