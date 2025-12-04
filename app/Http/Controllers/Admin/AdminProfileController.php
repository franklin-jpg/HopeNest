<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('Admin.profile.index');
    }
public function updateProfile(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'profile_image' => 'nullable|mimes:jpeg,png,jpg|image',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'required|string|max:20|unique:profiles,phone,' . optional($user->profile)->id,
        'address' => 'required|string|max:255',
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
        // dd($e->getMessage());
        return redirect()->back()->with('error', $e->getMessage());
    }
}


public function updatePassword(Request $request) {
$validated = $request->validate([
    'current_password' => 'required',
    'new_password' => 'required|min:6|confirmed'
], [
    'current_password.required' => ' current password is required'
]);

try {
    $user = Auth::user();
    // verify current password
    if (!Hash::check($validated['current_password'], $user->password)) {
        return back()->withErrors(['current_password' => 'current password is incorrect']);
    }
    
    //update password
    $user->update([
        'password' => Hash::make($validated['new_password']),
    ]);

    return redirect()->route('admin.profile.index')->with('success', 'password updated 
    successfully');
}catch (Exception $e) {
return redirect()->back()->with('error', 'Failed to update password' . $e->getMessage());
}
}

}
