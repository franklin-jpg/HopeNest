@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Profile Settings</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your account information and security</p>
    </div>

    <!-- Profile Information Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Profile Information</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your personal information</p>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PATCH')

           <!-- Profile Image -->
<div class="flex flex-col items-center mb-8">
    <div class="relative group">
        @php
            $user = auth()->user();
            $hasImage = $user->profile && $user->profile->profile_image;
            
            $name = $user->name ?? 'User';
            $nameParts = explode(' ', trim($name));
            $firstInitial = strtoupper($nameParts[0][0] ?? 'U');
            $lastInitial = isset($nameParts[1][0]) ? strtoupper($nameParts[1][0]) : '';
            $initials = $firstInitial . $lastInitial;
        @endphp

        <div class="relative">
            @if ($hasImage)
                <img id="profilePreview" 
                     src="{{ $user->profileImageUrl() }}" 
                     alt="Profile" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-primary/20 shadow-lg">
            @else
                <div id="profilePreview" 
                     class="w-32 h-32 rounded-full bg-primary text-white flex items-center justify-center text-3xl font-bold border-4 border-primary/20 shadow-lg">
                    {{ $initials }}
                </div>
            @endif

            <!-- Camera Icon Overlay -->
            <label for="profile_image" 
                   class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <i class="fas fa-camera text-white text-2xl"></i>
            </label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden">
        </div>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Click to upload new photo</p>
    @error('profile_image')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

            <!-- Form Fields Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter your full name">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter your email">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->profile->phone ?? '') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter your phone number">
                    </div>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Address
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->profile->address ?? '') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter your address">
                    </div>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg 
                               transition-colors duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Change Password</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your password to keep your account secure</p>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="space-y-6 max-w-2xl">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full pl-10 pr-12 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter current password">
                        <button type="button" onclick="togglePassword('current_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-eye" id="current_password_icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="new_password" name="new_password" required
                               class="w-full pl-10 pr-12 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Enter new password">
                        <button type="button" onclick="togglePassword('new_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-eye" id="new_password_icon"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 8 characters with letters and numbers</p>
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                               class="w-full pl-10 pr-12 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                      focus:ring-2 focus:ring-primary/50 focus:border-primary
                                      placeholder:text-gray-400 dark:placeholder:text-gray-500"
                               placeholder="Confirm new password">
                        <button type="button" onclick="togglePassword('new_password_confirmation')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg 
                               transition-colors duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-key"></i>
                    <span>Update Password</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Profile Image Preview
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace initials div with image
                    const img = document.createElement('img');
                    img.id = 'profilePreview';
                    img.src = e.target.result;
                    img.className = 'w-32 h-32 rounded-full object-cover border-4 border-primary/20 shadow-lg';
                    preview.replaceWith(img);
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Toggle Password Visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection