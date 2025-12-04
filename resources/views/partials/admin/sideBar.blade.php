 <aside id="sidebar" class="sidebar fixed md:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col border-r dark:border-gray-700">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b dark:border-gray-700">
                <a href="#" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-white sidebar-text">HopeNest</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-white bg-primary rounded-lg">
                            <i class="fas fa-home text-lg"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-users text-lg"></i>
                            <span class="sidebar-text">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.donations.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-donate text-lg"></i>
                            <span class="sidebar-text">Donations</span>
                        </a>
                    </li>


                   <li x-data="{ open: false }" class="relative">
    <!-- Dropdown Trigger -->
    <a href="javascript:void(0)"
       @click="open = !open"
       class="flex items-center justify-between gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
       :class="{ 'bg-gray-100 dark:bg-gray-700': open || $el.closest('li').querySelector('a[href*=''+window.location.pathname+'']')?.closest('ul') }"
    >
        <div class="flex items-center gap-3">
            <i class="fas fa-bullhorn text-lg"></i>
            <span class="sidebar-text">Campaigns</span>
        </div>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </a>

    <!-- Dropdown Menu -->
    <ul x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="ml-8 mt-2 space-y-1 border-l-2 border-indigo-500 pl-6"
    >


      <li>
            <a href="{{ route('admin.campaigns.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors {{ request()->routeIs('admin.campaigns.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>All Campaigns</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.analytics.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors {{ request()->routeIs('admin.campaigns.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Campaign Analytics</span>
            </a>
        </li>

       

    </ul>
</li>


                  
    











                   <li x-data="{ open: false }" class="relative">
    <!-- Dropdown Trigger -->
    <a href="javascript:void(0)"
       @click="open = !open"
       class="flex items-center justify-between gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
       :class="{ 'bg-gray-100 dark:bg-gray-700': open || $el.closest('li').querySelector('a[href*=''+window.location.pathname+'']')?.closest('ul') }"
    >
        <div class="flex items-center gap-3">
            <i class="fas fa-hands-helping text-lg"></i>
            <span class="sidebar-text">Volunteers</span>
        </div>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </a>

    <!-- Dropdown Menu -->
    <ul x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="ml-8 mt-2 space-y-1 border-l-2 border-red-300 pl-6"
    >


      <li>
            <a href="{{ route('admin.volunteers.index')  }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors {{ request()->routeIs('admin.campaigns.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
                <i class="fas fa-hands-helping text-lg"></i>
                <span>All volunteers</span>

                @php $pendingCount = \App\Models\Volunteer::pending()->count(); @endphp
        @if($pendingCount > 0)
            <span class="flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold rounded-full shadow-lg group-hover:shadow-xl transition-all duration-300 animate-pulse">
                <i class="fas fa-clock text-xs"></i>
                {{ $pendingCount }}
            </span>
        @endif
            </a>
        </li>

        <li>
            <a href="{{ route('admin.volunteer-hours.index')  }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors {{ request()->routeIs('admin.campaigns.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
                <i class="fas fa-clock text-lg"></i>
                <span>Volunteers Hour</span>
            </a>
        </li>

       

    </ul>
</li>


       <li x-data="{ open: false }" class="relative">
    <!-- Dropdown Trigger -->
    <a href="javascript:void(0)"
       @click="open = !open"
       class="flex items-center justify-between gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
       :class="{ 'bg-gray-100 dark:bg-gray-700': open }">
        <div class="flex items-center gap-3">
            <i class="fas fa-chart-bar text-lg"></i>
            <span class="sidebar-text">Reports</span>
        </div>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </a>

    <!-- Dropdown Menu -->
    <ul x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="ml-8 mt-2 space-y-1 border-l-2 border-orange-500 pl-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg py-2">
        
        <li>
            <a href="{{ route('admin.reports.donations.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-hand-holding-heart text-orange-500"></i>
                <span>Donation Reports</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.reports.campaigns.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-chart-line text-orange-500"></i>
                <span>Campaign Reports</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.volunteers-reports.index')}}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-users text-orange-500"></i>
                <span>Volunteer Reports</span>
            </a>
        </li>
    </ul>
</li>
<!-- BLOG POSTS DROPDOWN (Same style as Reports) -->
<li x-data="{ open: false }" class="relative">
    <!-- Dropdown Trigger -->
    <a href="javascript:void(0)"
       @click="open = !open"
       class="flex items-center justify-between gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
       :class="{ 'bg-gray-100 dark:bg-gray-700': open }">
        <div class="flex items-center gap-3">
            <i class="fas fa-blog text-lg"></i>
            <span class="sidebar-text">Blog & News</span>
        </div>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
           :class="{ 'rotate-180': open }"></i>
    </a>

    <!-- Dropdown Menu -->
    <ul x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="ml-8 mt-2 space-y-1 border-l-2 border-orange-500 pl-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg py-2">
        
        <li>
            <a href="{{ route('admin.blogs.blog.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-list-alt text-orange-500"></i>
                <span>All Posts</span>
            </a>
        </li>

       

        <li>
            <a href="{{ route('admin.blogs.categories.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-folder text-blue-500"></i>
                <span>Categories</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.blogs.tags.index') }}"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-md transition-colors">
                <i class="fas fa-tags text-purple-500"></i>
                <span>Tags</span>
            </a>
        </li>

       
    </ul>
</li>



     <a href="{{ route('admin.impact-stories.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>

                            <span class="sidebar-text">Impact Stories</span>
                        </a>
                    </li>
     

                      <li>
                        <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                           <i class="fa-regular fa-address-card"></i>
                            <span class="sidebar-text">Contacts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-cog text-lg"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t dark:border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg w-full">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>