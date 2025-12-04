@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class=" px-8 py-6">
                <h1 class="text-2xl font-bold text-black dark:text-gray-200">General Settings</h1>
                <p class=" dark:text-gray-200">Manage your HopeNest organization details</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Site Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? 'HopeNest') }}" required
                               class="w-full px-4 py-2 rounded-lg border dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $settings->email) }}" required
                               class="w-full px-4 py-2 rounded-lg border dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" required
                               class="w-full px-4  dark:text-gray-300 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business Hours</label>
                        <input type="text" name="business_hours" value="{{ old('business_hours', $settings->business_hours ?? 'Mon–Fri: 9AM–5PM') }}" required
                               class="w-full px-4 py-2 rounded-lg border dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Office Address</label>
                    <textarea name="address" rows="3" required
                              class="w-full px-4 py-2 rounded-lg border dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-indigo-500">{{ old('address', $settings->address) }}</textarea>
                </div>

                <!-- Logo & Favicon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Logo</label>
                        @if($settings->logo)
                            <div class="mb-4">
                                <img src="{{ Storage::url($settings->logo) }}" alt="Current Logo" class="h-16 rounded border dark:border-gray-600">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Favicon</label>
                        @if($settings->favicon)
                            <div class="mb-4">
                                <img src="{{ Storage::url($settings->favicon) }}" alt="Favicon" class="h-10 w-10 rounded border dark:border-gray-600">
                            </div>
                        @endif
                        <input type="file" name="favicon" accept=".png,.ico,.svg"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    </div>
                </div>

                <!-- Social Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Social Media Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <input type="url" name="facebook"   value="{{ old('facebook', $settings->facebook) }}"   placeholder="https://facebook.com/hopenest"   class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700">
                        <input type="url" name="twitter"    value="{{ old('twitter', $settings->twitter) }}"     placeholder="https://twitter.com/hopenest"    class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700">
                        <input type="url" name="instagram"  value="{{ old('instagram', $settings->instagram) }}" placeholder="https://instagram.com/hopenest"  class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700">
                        <input type="url" name="linkedin"   value="{{ old('linkedin', $settings->linkedin) }}"   placeholder="https://linkedin.com/company/hopenest" class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700">
                        <input type="url" name="youtube"    value="{{ old('youtube', $settings->youtube) }}"     placeholder="https://youtube.com/@hopenest"   class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-lg transition transform hover:scale-105">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection