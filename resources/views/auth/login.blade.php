<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HopeNest</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- iziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

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

<div class="flex flex-col md:flex-row w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- LEFT SIDE - Image & Info -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/2.jpg') }}" alt="HopeNest mission" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-4xl text-[#ff5722]"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-[#ff5722]">Welcome Back</h1>
                <p class="text-xl mb-8">Continue your journey of making a difference with <span class="font-semibold text-[#ff5722]">HopeNest</span></p>
            </div>

            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-2xl w-full max-w-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p>Secure & encrypted login</p>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-heart"></i>
                    </div>
                    <p>Track your donations</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-users"></i>
                    </div>
                    <p>Join our community</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE - Login Form -->
    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ff5722;">
                <i class="fas fa-sign-in-alt text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Login to HopeNest</h2>
            <p class="text-gray-600">Access your account to continue helping</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="pl-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722]"
                           placeholder="you@example.com">
                </div>
                @error('email')
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" id="password"
                           class="pl-10 pr-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722]"
                           placeholder="••••••••">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="password-toggle fas fa-eye-slash text-gray-400" id="togglePassword"></i>
                    </div>
                </div>
                @error('password')
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                           class="h-4 w-4 text-[#ff5722] focus:ring-[#ff5722] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>
                <div>
                    <a href="{{ route('password.reset')}}" class="text-sm font-medium text-[#ff5722] hover:underline">
                        Forgot Password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="animation-pulse-slow w-full text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center hover:opacity-90"
                    style="background:#ff5722;">
                <span>Login to Account</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-8 text-center border-t border-gray-200 pt-6">
            <p class="text-gray-600 text-sm">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-[#ff5722] hover:underline">
                    Create an account
                </a>
            </p>
        </div>

        <!-- Back to Home -->
        <div class="mt-4 text-center">
            <a href="" class="text-sm text-gray-500 hover:text-[#ff5722] transition">
                <i class="fas fa-home mr-1"></i>
                Back to  registration 
            </a>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

{{-- Toast notifications --}}
@if (session('success') || session('error') || session('info') || session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                iziToast.success({
                    title: 'Success',
                    message: @json(session('success')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('error'))
                iziToast.error({
                    title: 'Error',
                    message: @json(session('error')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('info'))
                iziToast.info({
                    title: 'Info',
                    message: @json(session('info')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('warning'))
                iziToast.warning({
                    title: 'Warning',
                    message: @json(session('warning')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @endif
        });
    </script>
@endif

</body>
</html>