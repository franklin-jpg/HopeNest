<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VolunteerProfileController extends Controller
{
    public function index()
    {
        return view('volunteer.profile.index');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $volunteer = $user->volunteer; // Assuming one-to-one relationship

        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:volunteers,phone,' . ($volunteer?->id ?? ''),
            'address' => 'nullable|string|max:500',
        ]);

        try {
            $imagePath = $volunteer?->profile_image;

            if ($request->hasFile('profile_image')) {
                // Delete old image
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $image = $request->file('profile_image');
                $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('profile-images', $fileName, 'public');
            }

            // Update User
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update or Create Volunteer Profile
            if (!$volunteer) {
                $volunteer = $user->volunteer()->create([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'profile_image' => $imagePath,
                ]);
            } else {
                $volunteer->update([
                    'phone' => $validated['phone'] ?? $volunteer->phone,
                    'address' => $validated['address'] ?? $volunteer->address,
                    'profile_image' => $imagePath ?? $volunteer->profile_image,
                ]);
            }

            return redirect()->back()->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->route('volunteer.profile.index')->with('success', 'Password updated successfully!');
    }
}