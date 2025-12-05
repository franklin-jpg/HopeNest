<header class="h-16 bg-white dark:bg-gray-800 dark:text-gray-200 shadow-sm flex items-center justify-between px-6 transition-colors duration-500">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="text-gray-600 dark:text-gray-300 hover:text-primary md:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Volunteer Dashboard</h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Dark Mode Toggle -->
        <button id="themeToggle" class="text-gray-600 dark:text-yellow-400 text-xl transition-transform duration-300 transform hover:scale-110">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>

        <!-- Notifications -->
        <button class="relative text-gray-600 dark:text-gray-300 hover:text-primary">
            <i class="fas fa-bell text-xl"></i>
        </button>

        <!-- Volunteer Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                
                @php
                    $user = auth()->user();
                    $name = $user->name ?? 'Volunteer';
                    $nameParts = explode(' ', trim($name));
                    $firstInitial = strtoupper($nameParts[0][0] ?? 'V');
                    $lastInitial = isset($nameParts[1][0]) ? strtoupper($nameParts[1][0]) : '';
                    $initials = $firstInitial . $lastInitial;
                    
                    // Check if user has profile with image
                    $hasImage = $user->profile && $user->profile->profile_image;
                @endphp

                <!-- Profile Image or Initials -->
                @if ($hasImage)
                    <img src="{{ $user->profileImageUrl() }}" 
                         alt="Volunteer" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-primary/20 shadow-sm">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm border-2 border-primary/20 shadow-sm">
                        {{ $initials }}
                    </div>
                @endif

                <!-- Name & Role (Hidden on mobile) -->
                <div class="hidden lg:block text-left">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white leading-none">{{ Str::limit($user->name, 18) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Volunteer</p>
                </div>

                <!-- Chevron Icon -->
                <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 hidden lg:block transition-transform duration-200"
                   :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden"
                 style="display: none;"
                 @click.stop>

                <!-- Welcome Header -->
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary/5 to-primary/10">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Welcome back!</p>
                    <p class="text-base font-semibold text-gray-800 dark:text-white mt-1">{{ $user->name }}</p>
                    <p class="text-xs text-primary dark:text-primary-400 mt-1 flex items-center gap-1">
                        <i class="fas fa-check-circle"></i>
                        <span>Approved Volunteer</span>
                    </p>
                </div>

                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('volunteer.profile.index') }}" 
                       class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-user-circle w-5 text-primary"></i>
                        <span>My Profile</span>
                    </a>

                    <a href="{{ route('volunteer.dashboard') }}" 
                       class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-tachometer-alt w-5 text-blue-600"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('volunteer.assigned-campaigns.index') }}" 
                       class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-tasks w-5 text-green-600"></i>
                        <span>My Campaigns</span>
                    </a>
                </div>

                <!-- Logout -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>