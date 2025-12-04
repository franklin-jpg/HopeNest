<!DOCTYPE html>
<html lang="en" class="transition-colors duration-500">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
    <base href="/public">
  <title>@yield('title', 'User Dashboard') | HopeNest</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- iziToast -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body {
      font-family: 'Inter', sans-serif;
      transition: background-color 0.4s ease, color 0.4s ease;
    }
    .sidebar {
      transition: all 0.3s ease;
    }
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.mobile-open {
        transform: translateX(0);
      }
    }

    /* 🌙 Smooth dark mode fade */
    * {
      transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
    }
  </style>

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#ff5722',
            secondary: '#ff7043',
          }
        }
      }
    }
  </script>
</head>

<!-- 🌙 Dark mode works based on class="dark" on <html> -->
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

  <div class="flex h-screen overflow-hidden">
    <!-- ✅ Keep overflow-hidden here to prevent full page scroll -->

    <!-- Sidebar -->
    @include('partials.user.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- ✅ This container controls the layout structure -->

      <!-- Top Bar -->
      @include('partials.user.topbar')

      <!-- Page Content - THIS IS THE SCROLLABLE AREA -->
      <main class="flex-1 overflow-y-auto overflow-x-hidden">
        <!-- ✅ Added overflow-y-auto to make this section scrollable -->
        @yield('content')
      </main>

      <!-- Footer -->
      @include('partials.user.footer')

    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <script>
    // Sidebar Toggle
    document.getElementById('toggleSidebar').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('mobile-open');
    });

    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('toggleSidebar');
      if (window.innerWidth < 768 && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('mobile-open');
      }
    });

    // 🌙 Dark Mode Script
    const htmlEl = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Load saved theme
    if (localStorage.theme === 'dark' || 
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      htmlEl.classList.add('dark');
      themeIcon.classList.replace('fa-moon', 'fa-sun');
    } else {
      htmlEl.classList.remove('dark');
    }

    // Toggle dark/light mode
    themeToggle.addEventListener('click', () => {
      htmlEl.classList.add('theme-changing');
      setTimeout(() => {
        htmlEl.classList.toggle('dark');
        localStorage.theme = htmlEl.classList.contains('dark') ? 'dark' : 'light';
        themeIcon.classList.toggle('fa-moon');
        themeIcon.classList.toggle('fa-sun');
      }, 0);
    });
  </script>

  {{-- Toast Notifications --}}
  @if (session('success') || session('error'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
          iziToast.success({ title: 'Success', message: @json(session('success')), position: 'topRight' });
        @elseif (session('error'))
          iziToast.error({ title: 'Error', message: @json(session('error')), position: 'topRight' });
        @endif
      });
    </script>
  @endif

  @stack('scripts')
</body>
</html>