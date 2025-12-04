<aside id="sidebar" class="sidebar fixed md:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col">
  <!-- Logo -->
  <div class="h-16 flex items-center justify-between px-6 border-b dark:border-gray-700">
    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
      <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
      </div>
      <span class="text-xl font-bold text-gray-800 dark:text-white">HopeNest</span>
    </a>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 overflow-y-auto py-4">
    <ul class="space-y-1 px-3">
      <li>
        <a href="{{ route('user.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('user.dashboard') ? 'text-white bg-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
          <i class="fas fa-home text-lg"></i>
          <span>Dashboard</span>
        </a>
      </li>
      
     
      
      <li>
        <a href="{{ route('user.donations.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('user.donations.*') ? 'text-white bg-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
          <i class="fas fa-history text-lg"></i>
          <span>My Donations</span>
        </a>
      </li>
      
      <li>
        <a href="{{ route('user.favorites.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
          <i class="fas fa-bullhorn text-lg"></i>
          <span> Favourite Campaigns</span>
        </a>
      </li>
      
      
      
      <li>
        <a href="{{ route('user.profile.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('user.profile.*') ? 'text-white bg-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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
      <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg w-full transition">
        <i class="fas fa-sign-out-alt text-lg"></i>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>