<header class="h-16 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-6 border-b dark:border-gray-700">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="text-gray-600 dark:text-gray-300 hover:text-primary">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">User Dashboard</h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Dark Mode Toggle -->
        <button id="themeToggle" type="button" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i id="themeIcon" class="fas fa-moon text-xl"></i>
        </button>

        <!-- Notifications -->
        <button class="relative text-gray-600 dark:text-gray-300 hover:text-primary p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-bell text-xl"></i>
            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" 
                    class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                @php
                    $user = auth()->user();
                    $hasImage = $user->profile && $user->profile->profile_image;

                    $name = $user->name ?? 'User';
                    $nameParts = explode(' ', trim($name));

                    $firstInitial = strtoupper($nameParts[0][0] ?? 'U');
                    $lastInitial = isset($nameParts[1][0]) ? strtoupper($nameParts[1][0]) : '';
                    $initials = $firstInitial . $lastInitial;
                @endphp

                @if ($hasImage)
                    <img src="{{ $user->profileImageUrl() }}" 
                         alt="user-image" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-primary/20">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold text-sm border-2 border-primary/20">
                        {{ $initials }}
                    </div>
                @endif

                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucwords($user->role ?? 'User') }}</p>
                </div>

                <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 hidden md:block" 
                   :class="{ 'rotate-180': open }" 
                   style="transition: transform 0.2s;"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50"
                 style="display: none;">
                
                <!-- Welcome Header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Welcome!</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $user->name }}</p>
                </div>

                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('user.profile.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-user-circle text-base w-5"></i>
                        <span>My Account</span>
                    </a>

                    <a href="{{ route('user.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-tachometer-alt text-base w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('user.donations.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-hand-holding-heart text-base w-5"></i>
                        <span>My Donations</span>
                    </a>
                </div>

                <!-- Logout -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                    <a href="javascript:void(0)" 
                       onclick="document.getElementById('logout-form').submit();"
                       class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-sign-out-alt text-base w-5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hidden Logout Form -->
        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
            @csrf
        </form>
    </div>
</header>