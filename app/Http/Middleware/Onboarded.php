<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Onboarded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has completed onboarding
        if (Auth::check() && !Auth::user()->is_onboarding) {
            return $next($request);
        }

        // Redirect to the onboarding page if not onboarded
        if(Auth::user()->is_onboarding) {
            return redirect()->route('onboarding.' . Auth::user()->onboarding_step);
        }

        return redirect()->route('login');
    }
}
