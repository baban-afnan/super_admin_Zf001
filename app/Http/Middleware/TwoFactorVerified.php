<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->two_factor_enabled) {
            if (!$request->session()->get('two_factor_verified')) {
                // Generate code if not already present or redirect to verification page
                if (!$user->two_factor_code) {
                    // This case should ideally be handled during login, but as a fallback:
                    return redirect()->route('verify.resend');
                }
                
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
