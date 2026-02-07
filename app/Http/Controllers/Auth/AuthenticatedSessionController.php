<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt authentication
        $request->authenticate();

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if 2FA is enabled
        if ($user->two_factor_enabled) {
            // Generate 6-digit code
            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $user->update([
                'two_factor_code' => $code,
                'two_factor_expires_at' => now()->addMinutes(10),
            ]);

            // Clear verified status in session
            $request->session()->forget('two_factor_verified');

            // Send email
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TwoFactorCode($code));
            } catch (\Exception $e) {
                // Log error but continue (middleware will handle redirection)
                \Illuminate\Support\Facades\Log::error("Failed to send 2FA mail: " . $e->getMessage());
            }

            return redirect()->route('verify.index');
        }

        // Allowed roles
        $allowedRoles = ['super_admin'];

        // Check if user's role is not allowed
        if (!in_array($user->role, $allowedRoles)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

          

            // Option 1: Redirect back to login with an error message
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Access denied. Your account is not authorized to access this area.']);
        }

        // Redirect allowed users to dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
