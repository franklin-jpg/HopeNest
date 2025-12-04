<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | HopeNest</title>

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
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

<div class="flex flex-col md:flex-row w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- LEFT SIDE - Image & Info -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/3.jpg') }}" alt="HopeNest helping children" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-hands-helping text-4xl text-[#ff5722]"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-[#ff5722]">Reset Your Password</h1>
                <p class="text-xl mb-8">We’ll help you get back to making a difference</p>
            </div>

            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-2xl w-full max-w-md text-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <p>Check your email for reset link</p>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p>Secure & encrypted process</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p>Link expires in 2 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE - Forgot Password Form -->
    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ff5722;">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Forgot Password?</h2>
            <p class="text-gray-600">Enter your email to receive a password reset link</p>
        </div>

        <!-- Success Message (if email sent) -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('email.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1"></i> Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}" 
                        required
                        class="input-focus pl-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722] focus:ring-2"
                        placeholder="you@example.com"
                    >
                </div>
                @error('email')
                    <span class="text-sm text-red-600 mt-1 block">
                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="animation-pulse-slow w-full text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center hover:opacity-90 shadow-lg"
                    style="background:#ff5722;">
                <span>Send Reset Link</span>
                <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-8 text-center border-t border-gray-200 pt-6">
            <p class="text-gray-600 text-sm">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-[#ff5722] hover:underline">
                    Login here
                </a>
            </p>
        </div>

        <!-- Back to Home -->
        <div class="mt-4 text-center">
            <a href="{{ route('welcome') }}" class="text-sm text-gray-500 hover:text-[#ff5722] transition">
                <i class="fas fa-home mr-1"></i>
                Back to HopeNest Home
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success') || session('status'))
            iziToast.success({
                title: 'Success!',
                message: '{{ session('status') ?? 'Password reset link sent!' }}',
                position: 'topRight',
                timeout: 6000,
                progressBar: true,
                transitionIn: 'fadeInLeft',
            });
        @endif

        @if ($errors->any())
            iziToast.error({
                title: 'Error',
                message: 'Please check the form and try again.',
                position: 'topRight',
                timeout: 5000,
            });
        @endif
    });
</script>

</body>
</html>