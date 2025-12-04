<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <base href="/public">
    
   
    <title>Admin Dashboard | HopeNest</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- iziToast -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
<!-- Add this small CSS fix once in your main layout (inside <head> or a <style> tag) -->
<style>
    /* THIS IS THE ONLY FIX YOU NEED */
    body {
        padding-top: 64px; /* exactly the height of your header (h-16 = 64px) */
    }
    
    /* If you ever change header height, just update this value */
    @media (max-width: 640px) {
        body { padding-top: 56px; } /* optional: smaller padding on mobile if you want */
    }
</style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }
    </style>

<style>
/* Slider Container */
.urgent-campaigns-slider {
    position: relative;
    padding: 0 50px;
}

.campaign-slide {
    padding: 0 15px;
}

.campaign-slide-inner {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.campaign-slide-inner:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

/* Image Styling */
.img-box {
    position: relative;
    overflow: hidden;
    height: 280px;
}

.img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.campaign-slide-inner:hover .img-box img {
    transform: scale(1.05);
}

/* Overlay */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.campaign-slide-inner:hover .overlay {
    opacity: 1;
}

/* Content Area */
.content {
    padding: 25px 20px;
}

.content .title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.4;
    min-height: 56px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.content p {
    color: #666;
    margin-bottom: 20px;
    min-height: 60px;
}

/* Progress Bar */
.progress-box {
    margin-bottom: 20px;
}

.bar {
    background: #f0f0f0;
    border-radius: 10px;
    height: 10px;
    overflow: hidden;
}

.bar-inner {
    background: linear-gradient(90deg, #28a745 0%, #34ce57 100%);
    height: 100%;
    border-radius: 10px;
    position: relative;
    transition: width 1s ease;
}

.count-text {
    position: absolute;
    right: 5px;
    top: -25px;
    color: #333;
    font-weight: 600;
    font-size: 12px;
}

/* Donation Info */
.donate {
    padding-top: 15px;
    border-top: 1px solid #eee;
    font-size: 14px;
    font-weight: 500;
}

.donate span {
    color: #333;
}

/* Navigation Arrows */
.slider-navigation {
    position: relative;
    margin-top: 30px;
    text-align: center;
}

.slider-prev,
.slider-next {
    width: 45px;
    height: 45px;
    background: #fff;
    border: 2px solid #28a745;
    color: #28a745;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 20px;
    margin: 0 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.slider-prev:hover,
.slider-next:hover {
    background: #28a745;
    color: #fff;
    transform: scale(1.1);
}

.slider-prev:disabled,
.slider-next:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.slider-prev:disabled:hover,
.slider-next:disabled:hover {
    background: #fff;
    color: #28a745;
    transform: scale(1);
}

/* Slick Slider Dots (if enabled) */
.urgent-campaigns-slider .slick-dots {
    bottom: -40px;
}

.urgent-campaigns-slider .slick-dots li button:before {
    font-size: 12px;
    color: #28a745;
}

.urgent-campaigns-slider .slick-dots li.slick-active button:before {
    color: #28a745;
}

/* Responsive */
@media (max-width: 991px) {
    .urgent-campaigns-slider {
        padding: 0 40px;
    }
    
    .img-box {
        height: 250px;
    }
}

@media (max-width: 767px) {
    .urgent-campaigns-slider {
        padding: 0 30px;
    }
    
    .slider-prev,
    .slider-next {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .content .title {
        font-size: 18px;
        min-height: auto;
    }
    
    .content p {
        min-height: auto;
    }
}
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notifications', () => ({
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadNotifications();

            Echo.private(`App.Models.User.${@js(auth()->id())}`)
                .notification((notification) => {
                    this.notifications.unshift({
                        id: notification.id,
                        title: notification.title,
                        message: notification.message,
                        url: notification.url,
                        icon: notification.icon || 'bell',
                        time: 'Just now'
                    });
                    this.unreadCount++;
                    this.playSound();
                });

            window.addEventListener('mark-all-read', () => this.markAllAsRead());
        },

        loadNotifications() {
            axios.get('/admin/notifications')
                .then(r => {
                    this.notifications = r.data.notifications;
                    this.unreadCount = r.data.unread_count;
                });
        },

        markAllAsRead() {
            axios.post('/admin/notifications/read')
                .then(() => {
                    this.unreadCount = 0;
                    this.loadNotifications();
                });
        },

        playSound() {
            const audio = new Audio('/sounds/notification.mp3');
            audio.play().catch(() => {});
        }
    }));
});
</script>
    <script>
        // Dark mode initialization
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    

    <!-- Slick Slider CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>


</head>

<body class="bg-gray-50 dark:bg-gray-900">
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    @include('partials.admin.sideBar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Bar -->
        @include('partials.admin.header')

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </16main>

        <!-- Footer -->
        @include('partials.admin.footer')

    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>


<script>
// Notification Functions
function loadNotifications() {
    const container = document.getElementById('notifications-list');
    const loading = document.getElementById('notifications-loading');
    const empty = document.getElementById('notifications-empty');
    
    loading.style.display = 'block';
    container.innerHTML = '';
    empty.style.display = 'none';
    
    fetch('/admin/notifications/latest')
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.notifications.length === 0) {
                empty.style.display = 'block';
                return;
            }
            
            data.notifications.forEach(notification => {
                const notificationHtml = createNotificationElement(notification);
                container.innerHTML += notificationHtml;
            });
            
            // Update badge
            updateNotificationBadge(data.unread_count);
        })
        .catch(error => {
            console.error('Error:', error);
            loading.style.display = 'none';
            empty.style.display = 'block';
        });
}

function createNotificationElement(notification) {
    const icons = {
        'user_registered': { icon: 'fa-user-plus', color: 'text-green-500', bg: 'bg-green-100 dark:bg-green-900/30' },
        'donation_received': { icon: 'fa-hand-holding-heart', color: 'text-pink-500', bg: 'bg-pink-100 dark:bg-pink-900/30' },
        'contact_message': { icon: 'fa-envelope', color: 'text-yellow-500', bg: 'bg-yellow-100 dark:bg-yellow-900/30' },
        'volunteer_application': { icon: 'fa-hands-helping', color: 'text-purple-500', bg: 'bg-purple-100 dark:bg-purple-900/30' },
    };
    
    const iconData = icons[notification.type] || { icon: 'fa-bell', color: 'text-blue-500', bg: 'bg-blue-100 dark:bg-blue-900/30' };
    const readClass = notification.read ? '' : 'bg-blue-50 dark:bg-blue-900/10';
    
    return `
        <a href="${notification.url}" 
           class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-b-0 ${readClass}">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full ${iconData.bg} flex items-center justify-center">
                        <i class="fas ${iconData.icon} ${iconData.color}"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 dark:text-white ${notification.read ? '' : 'font-semibold'}">
                        ${notification.message}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <i class="far fa-clock mr-1"></i>${notification.time}
                    </p>
                </div>
                ${!notification.read ? '<span class="w-2 h-2 bg-primary rounded-full flex-shrink-0 mt-2"></span>' : ''}
            </div>
        </a>
    `;
}

function markAllAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
            updateNotificationBadge(0);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

// Refresh notifications every 30 seconds
setInterval(() => {
    fetch('/admin/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.count);
        })
        .catch(error => console.error('Error:', error));
}, 30000);
</script>

<script>
    // Dark Mode Toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (themeToggleBtn) {
        // Set initial icon state
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon?.classList.remove('hidden');
            themeToggleDarkIcon?.classList.add('hidden');
        } else {
            themeToggleDarkIcon?.classList.remove('hidden');
            themeToggleLightIcon?.classList.add('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }

    // Toggle Sidebar
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open');
            }
        });
    }

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        if (window.innerWidth < 768 && sidebar && toggleBtn && 
            !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('mobile-open');
        }
    });


     </script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    @if (session('success') || session('error') || session('info') || session('warning'))
<script>
        // iziToast Messages
        @if (session('success'))
            iziToast.success({
                title: 'Success',
                message: @json(session('success')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('error'))
            iziToast.error({
                title: 'Error',
                message: @json(session('error')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('info'))
            iziToast.info({
                title: 'Info',
                message: @json(session('info')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('warning'))
            iziToast.warning({
                title: 'Warning',
                message: @json(session('warning')),
                position: 'topRight',
                timeout: 5000
            });
        @endif

        </script>
    @endif
       
    </script>
<!-- Slick Slider JavaScript -->
<script>
$(document).ready(function(){
    // Check if there are campaigns to show
    if ($('.urgent-campaigns-slider .campaign-slide').length > 0) {
        // Initialize Slick Slider
        $('.urgent-campaigns-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            pauseOnHover: true,
            prevArrow: $('.slider-prev'),
            nextArrow: $('.slider-next'),
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: true
                    }
                }
            ]
        });

        // Animate progress bars when they come into view
        $('.urgent-campaigns-slider').on('afterChange', function(event, slick, currentSlide){
            animateProgressBars();
        });

        // Initial animation
        animateProgressBars();
    }

    function animateProgressBars() {
        $('.slick-active .animated-bar').each(function() {
            var percent = $(this).data('percent');
            $(this).css('width', percent);
        });
    }
});


</script>

{{-- THIS IS WHERE YOUR @push('scripts') GOES --}}
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowrift-core@1.1.0/dist/flowrift-core.js"></script>
<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Slick Slider JS -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


</body>
</html>