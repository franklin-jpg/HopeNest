<header class="h-16 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-6 border-b dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Admin Dashboard</h1>
    </div>

    <div class="flex items-center gap-4">

        <!-- Dark Mode Toggle -->
        <button id="theme-toggle" type="button" class="text-gray-600 dark:text-gray-300 hover:text-primary p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i id="theme-toggle-dark-icon" class="fas fa-moon hidden text-xl"></i>
            <i id="theme-toggle-light-icon" class="fas fa-sun hidden text-xl"></i>
        </button>

        <!-- Notifications Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open; if(open) loadNotifications()"
                    class="relative text-gray-600 dark:text-gray-300 hover:text-primary p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fas fa-bell text-xl"></i>
                <span id="notification-badge"
                      class="absolute top-1 right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center font-medium text-[10px]"
                      style="display: {{ auth()->user()->unreadNotifications->count() > 0 ? 'flex' : 'none' }}">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 z-[9999] overflow-hidden"
                 style="display: none;"
                 @click.stop>
                 
                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Notifications</h3>
                    <a href="{{ route('admin.notifications.index') }}" class="text-xs text-primary hover:underline font-medium">
                        View All
                    </a>
                </div>

                <!-- Notifications List -->
                <div id="notifications-container" class="max-h-96 overflow-y-auto">
                    <div id="notifications-loading" class="py-8 text-center">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Loading...</p>
                    </div>
                    <div id="notifications-list"></div>
                    <div id="notifications-empty" class="py-12 text-center hidden">
                        <i class="fas fa-bell-slash text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">No notifications yet</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-xs bg-gray-50 dark:bg-gray-900/50">
                    <button onclick="markAllAsRead()" class="text-primary hover:underline font-medium">
                        <i class="fas fa-check-double mr-1"></i>Mark all as read
                    </button>
                    <a href="{{ route('admin.notifications.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">
                        Settings
                    </a>
                </div>
            </div>
        </div>

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
                    <img src="{{ $user->profileImageUrl() }}" alt="user" class="w-10 h-10 rounded-full object-cover border-2 border-primary/20">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold text-sm border-2 border-primary/20">
                        {{ $initials }}
                    </div>
                @endif

                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucwords($user->role ?? 'admin') }}</p>
                </div>
                <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 hidden md:block transition-transform" 
                   :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Profile Menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 z-[9999] overflow-hidden"
                 style="display: none;"
                 @click.stop>

                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Welcome!</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $user->name }}</p>
                </div>

                <div class="py-2">
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-user-circle w-5"></i>
                        <span>My Account</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog w-5"></i>
                        <span>Settings</span>
                    </a>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                    <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();"
                       class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hidden Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</header>

