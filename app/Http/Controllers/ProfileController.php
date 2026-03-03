<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('profile.edit', [
            'user' => $user,
            'firstName' => $user->display_first_name,
            'lastName' => $user->display_last_name,
            'photo' => $user->profile_photo_url,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if (!empty($user->photo)) {
                $oldPath = $user->photo;
                
                // If the stored path is a full URL, attempt to extract the relative storage path
                $storageUrl = asset('storage/');
                if (strpos($oldPath, $storageUrl) === 0) {
                    $oldPath = str_replace($storageUrl, '', $oldPath);
                }
                
                $oldPath = ltrim($oldPath, '/');

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Define folder and unique filename as requested
            $folder = 'uploads/profile_photos';
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            
            // Store the file using 'public' disk
            $path = $request->file('photo')->storeAs($folder, $filename, 'public');
            
            // Save FULL URL to DB for "easy tracking"
            $user->photo = asset('storage/' . $path);
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-photo-updated');
    }

    /**
     * Update the user's transaction PIN.
     */
    public function updatePin(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'pin' => ['required', 'digits:5', 'confirmed'],
        ]);

        $user = $request->user();

        $user->pin = Hash::make($request->pin);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'pin-updated');
    }
}
