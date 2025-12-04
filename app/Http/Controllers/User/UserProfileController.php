<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_image' => 'nullable|mimes:jpeg,png,jpg|image|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:profiles,phone,' . optional($user->profile)->id,
            'address' => 'nullable|string|max:255',
        ]);

        try {
            // Get existing profile or create new one if it doesn't exist
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
            $imagePath = $profile->profile_image;

            // Handle profile image if uploaded
            if ($request->hasFile('profile_image')) {
                // Delete old image if it exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                // Store new image
                $image = $request->file('profile_image');
                $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('profile-images', $fileName, 'public');
            }

            // Update user info
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update profile info
            $profile->fill([
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'profile_image' => $imagePath,
            ])->save();

            return redirect()->back()->with('success', 'Your profile has been updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ], [
            'current_password.required' => 'Current password is required',
            'new_password.min' => 'New password must be at least 8 characters',
            'new_password.confirmed' => 'Password confirmation does not match'
        ]);

        try {
            $user = Auth::user();
            
            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            
            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            return redirect()->route('user.profile.index')->with('success', 'Password updated successfully');
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }
}