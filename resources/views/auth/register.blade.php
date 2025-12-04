{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | HopeNest</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- iziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fff7f0 0%, #ffe0c2 100%);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #ff7e2f 0%, #ff5e3a 100%);
        }

        .animation-pulse-slow {
            animation: pulse-slow 3s infinite;
        }

        @keyframes pulse-slow {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .password-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .password-toggle:hover {
            color: #ff7e2f;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

<div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- LEFT SIDE -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/3.jpg') }}" alt="HopeNest Community" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white p-10 text-center">
            <h1 class="text-4xl font-bold mb-4 text-orange-400">Join HopeNest</h1>
            <p class="text-lg mb-8 max-w-md">Be part of the mission to feed, educate, and empower communities in need.</p>

            <div class="bg-white/20 backdrop-blur-md p-6 rounded-2xl w-full max-w-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center mr-3">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <p>Empower the less privileged</p>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center mr-3">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <p>Grow sustainability</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center mr-3">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <p>Join a caring community</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE (FORM) -->
    <div class="md:w-1/2 p-8 md:p-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
            <p class="text-gray-600 mt-2">Be a part of HopeNest and bring hope to lives.</p>
        </div>

        <form method="POST" action="{{ route('submit.user') }}" class="space-y-6">
            @csrf

            <!-- FULL NAME -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="pl-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-orange-500 focus:ring-orange-500"
                           placeholder="John Danny">
                </div>
                @error('name')
                    <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="pl-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-orange-500 focus:ring-orange-500"
                           placeholder="you@example.com">
                </div>
                @error('email')
                    <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" id="password"
                           class="pl-10 pr-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-orange-500 focus:ring-orange-500"
                           placeholder="••••••••">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="password-toggle fas fa-eye-slash text-gray-400" id="togglePassword"></i>
                    </div>
                </div>
                @error('password')
                    <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="confirmPassword"
                           class="pl-10 pr-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-orange-500 focus:ring-orange-500"
                           placeholder="••••••••">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="password-toggle fas fa-eye-slash text-gray-400" id="toggleConfirmPassword"></i>
                    </div>
                </div>
            </div>

            <!-- RECAPTCHA -->
            <div class="flex justify-center">
                <div class="g-recaptcha"
                     data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"
                     data-theme="light"
                     data-size="normal"></div>
            </div>
            @error('g-recaptcha-response')
                <p class="text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror

            <!-- SUBMIT -->
            <button type="submit"
                    class="animation-pulse-slow w-full gradient-bg text-white py-3 px-4 rounded-lg font-medium hover:opacity-90 transition duration-300 flex items-center justify-center">
                <span>Create Account</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-4 text-gray-500">or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- SOCIAL LOGIN -->
        <div class="grid grid-cols-2 gap-4">
            <a href=""
               class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium flex items-center justify-center hover:bg-gray-50 transition duration-300">
                <i class="fab fa-google text-red-500 mr-2"></i>
                Google
            </a>
            <a href=""
               class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium flex items-center justify-center hover:bg-gray-50 transition duration-300">
                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                Facebook
            </a>
            
        </div>

        <!-- LOGIN LINK -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-500">Log in</a>
            </p>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
    // Password visibility toggles
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
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

</body>
</html>
