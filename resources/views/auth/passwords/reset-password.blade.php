<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | HopeNest</title>

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

        .animation-pulse-slow {
            animation: pulse-slow 3s infinite;
        }

        @keyframes pulse-slow {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

<div class="flex flex-col md:flex-row w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- LEFT SIDE - Image & Info -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/2.jpg') }}" alt="HopeNest secure" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-lock-open text-4xl text-[#ff5722]"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-[#ff5722]">Welcome Back!</h1>
                <p class="text-xl mb-8">Your account is almost ready</p>
            </div>

            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-2xl w-full max-w-md text-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p>256-bit encryption</p>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-key"></i>
                    </div>
                    <p>Strong password required</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-heart"></i>
                    </div>
                    <p>You're helping save lives</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE - Reset Password Form -->
    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ff5722;">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Reset Your Password</h2>
            <p class="text-gray-600">Choose a strong, unique password</p>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('reset.password.submit', $token) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request()->email }}">

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    New Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        required
                        class="input-focus pl-10 pr-12 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722]"
                        placeholder="••••••••"
                    >
                    <button type="button" onclick="togglePassword('password', this)" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-[#ff5722]">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div class="password-strength bg-gray-200" id="strength-bar"></div>
                <p class="text-xs text-gray-500 mt-1" id="strength-text">Enter at least 8 characters</p>
                @error('password')
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        required
                        class="input-focus pl-10 pr-12 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722]"
                        placeholder="•••••••"
                    >
                    <button type="button" onclick="togglePassword('password_confirmation', this)" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-[#ff5722]">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="animation-pulse-slow w-full text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center shadow-lg hover:opacity-90"
                    style="background:#ff5722;">
                <span>Reset Password</span>
                <i class="fas fa-arrow-right ml-2"></i>
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
            <a href="{{ route('logout') }}" class="text-sm text-gray-500 hover:text-[#ff5722] transition">
                <i class="fas fa-home mr-1"></i>
                Back to HopeNest Home
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
    // Toggle Password Visibility
    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }

    // Password Strength Meter
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 25;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 25;

        strengthBar.style.width = strength + '%';
        if (strength < 50) {
            strengthBar.style.backgroundColor = '#ef4444';
            strengthText.textContent = 'Weak password';
            strengthText.style.color = '#ef4444';
        } else if (strength < 75) {
            strengthBar.style.backgroundColor = '#f59e0b';
            strengthText.textContent = 'Medium password';
            strengthText.style.color = '#f59e0b';
        } else {
            strengthBar.style.backgroundColor = '#10b981';
            strengthText.textContent = 'Strong password!';
            strengthText.style.color = '#10b981';
        }
    });

    // Toast Notifications
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('status'))
            iziToast.success({
                title: 'Success!',
                message: '{{ session('status') }}',
                position: 'topRight',
                timeout: 6000,
                progressBar: true,
                transitionIn: 'fadeInLeft'
            });
        @endif

        @if ($errors->any())
            iziToast.error({
                title: 'Error',
                message: 'Please fix the errors below.',
                position: 'topRight',
                timeout: 5000
            });
        @endif
    });
</script>

</body>
</html>