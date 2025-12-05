<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class VolunteerProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('volunteer.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            // Update user basic info
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Get or create profile
            $profile = $user->profile;
            
            if (!$profile) {
                $profile = Profile::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            }

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                Log::info('Profile image upload started for user: ' . $user->id);
                
                // Delete old image if exists
                if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                    Storage::disk('public')->delete($profile->profile_image);
                    Log::info('Old profile image deleted: ' . $profile->profile_image);
                }

                // Store new image
                $imagePath = $request->file('profile_image')->store('profiles', 'public');
                Log::info('New profile image stored: ' . $imagePath);
                
                $profile->profile_image = $imagePath;
            }

            // Update other profile fields
            $profile->phone = $validated['phone'] ?? $profile->phone;
            $profile->address = $validated['address'] ?? $profile->address;
            $profile->save();

            Log::info('Profile updated successfully for user: ' . $user->id);

            return redirect()
                ->route('volunteer.profile.index')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        try {
            // Check if current password is correct
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()
                    ->back()
                    ->with('error', 'Current password is incorrect.');
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            Log::info('Password updated successfully for user: ' . $user->id);

            return redirect()
                ->route('volunteer.profile.index')
                ->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to update password. Please try again.');
        }
    }
}