<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // GET: Show the settings form
    public function edit()
    {
        $settings = Setting::firstOrCreate([]); // Always return one row
        return view('admin.settings.edit', compact('settings'));
    }

    // POST: Update settings
    public function update(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        $request->validate([
            'site_name'      => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string',
            'business_hours' => 'required|string|max:255',
            'facebook'       => 'nullable|url',
            'twitter'        => 'nullable|url',
            'instagram'      => 'nullable|url',
            'linkedin'       => 'nullable|url',
            'youtube'        => 'nullable|url',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon'        => 'nullable|image|mimes:png,ico,svg|max:512',
        ]);

        $data = $request->except(['logo', 'favicon']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($data);

        return back()->with('success', 'Settings updated successfully!');
    }
}