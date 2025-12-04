  <aside id="sidebar" class="sidebar fixed md:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 dark:text-gray-200 shadow-lg flex flex-col transition-all duration-500">
            <!-- Logo -->
            @if(isset($stats))
    <!-- Use $stats here -->
   
@else
    <!-- Optional: fallback or nothing -->
@endif
@if(isset($stats))
    <!-- use $stats['tasks_pending'] ?? 0 here -->
@else
    <!-- fallback, or display nothing, or a message -->
@endif

            <div class="h-16 flex items-center justify-between px-6 border-b dark:border-gray-700">
                <a href="#" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-gray-100">HopeNest</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('volunteer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-white bg-primary rounded-lg">
                            <i class="fas fa-home text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('volunteer.assigned-campaigns.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-tasks text-lg"></i>
                            <span>My Tasks</span>
                           
                        </a>
                    </li>
                    <li>
    <a href="{{ route('volunteer.volunteer-donations.index') }}"
       class="flex items-center px-4 py-2 text-sm font-medium rounded-md">
        <i class="fas fa-heart mr-3"></i>
        My Donations
    </a>
</li>
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-clock text-lg"></i>
                            <span>Hours Logged</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-certificate text-lg"></i>
                            <span>Certifications</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('volunteer.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-user text-lg"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t dark:border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg w-full">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>