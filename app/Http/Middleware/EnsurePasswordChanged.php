<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the logged-in user has not changed their password
        if (Auth::check() && Auth::user()->role === 'client' && !Auth::user()->password_changed) {
            // Redirect to the client dashboard with a modal flag
            return redirect()->route('client.dashboard')->with([
                'showModal' => true,
                'info' => 'You must change your password before accessing other pages.',
            ]);
        }

        return $next($request);
    }
}
