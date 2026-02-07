<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    /**
     * Display the 2FA form.
     */
    public function index()
    {
        return view('auth.two-factor');
    }

    /**
     * Validate the OTP.
     */
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if ($request->two_factor_code == $user->two_factor_code) {
            
            // Check expiry
            if ($user->two_factor_expires_at->lt(now())) {
                $user->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);
                return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code has expired. Please resend.']);
            }

            $user->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);
            session(['two_factor_verified' => true]);

            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code you entered is incorrect.']);
    }

    /**
     * Resend the OTP.
     */
    public function resend()
    {
        $user = auth()->user();
        
        $user->update([
            'two_factor_code' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
            return redirect()->back()->with('status', 'The two factor code has been resent.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Unable to send email. Please try again later.']);
        }
    }
    
    /**
     * Toggle 2FA status
     */
    public function toggle(Request $request)
    {
        $user = auth()->user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();
        
        $status = $user->two_factor_enabled ? 'enabled' : 'disabled';
        return redirect()->back()->with('status', "Two Factor Authentication has been {$status}.");
    }
}
