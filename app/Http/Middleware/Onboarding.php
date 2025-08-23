<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Onboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and in the onboarding process
        if (Auth::check() && Auth::user()->is_onboarding) {
            return $next($request);
        }

        // If the user is not authenticated or not in onboarding, redirect to login
        return redirect()->route('login');
    }
}
