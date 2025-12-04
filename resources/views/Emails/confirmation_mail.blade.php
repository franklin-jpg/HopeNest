<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your HopeNest Verification Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-b from-orange-50 to-amber-50">

<div class="max-w-xl mx-auto my-10 bg-white rounded-3xl shadow-2xl overflow-hidden border border-orange-100">

    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-center py-10 px-6">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-4xl font-bold tracking-tight">HopeNest</h1>
        <p class="text-lg opacity-95 mt-2">Your Secure Verification Code</p>
    </div>

    <!-- Body -->
    <div class="p-10 text-gray-800">
        <p class="text-xl mb-6 leading-relaxed">
            Hello <span class="font-bold text-orange-600">{{ $user->name ?? 'Kind Donor' }}</span>,
        </p>

        <p class="text-lg mb-6 leading-relaxed">
            Thank you for choosing <span class="font-bold text-orange-600">HopeNest</span>.  
            We’ve sent you a one-time verification code to complete your password reset.
        </p>

        <!-- OTP Box -->
        <div class="text-center my-10">
            <div class="inline-block bg-orange-50 border-4 border-dashed border-orange-300 rounded-3xl px-12 py-8 shadow-inner">
                <p class="text-sm uppercase tracking-wider text-orange-600 font-medium mb-3">Your Code</p>
                <div class="text-5xl font-extrabold text-orange-700 tracking-widest select-all">
                    {{ $otp_code }}
                </div>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
            <p class="text-sm font-medium text-amber-800 mb-2">
                This code expires in <strong>2 minutes</strong>
            </p>
            <p class="text-sm text-amber-700">
                For your security, never share this code. HopeNest will never call or message you asking for it.
            </p>
        </div>

        <p class="text-lg mb-6">
            If you didn’t request this, please ignore this email — your account remains safe.
        </p>

        <div class="mt-10 pt-8 border-t border-gray-200">
            <p class="text-lg">With warmth and gratitude,</p>
            <p class="text-xl font-bold text-orange-600 mt-1">The HopeNest Family</p>
            <p class="text-sm text-gray-600 mt-3">
                Together, we’re building hope — one donation at a time.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-r from-orange-100 to-amber-100 text-center py-8 px-6 border-t-4 border-orange-400">
        <p class="text-sm text-gray-700 mb-3">
            Need help? Reach out anytime at 
            <a href="mailto:support@hopenest.org" class="font-bold text-orange-600 hover:underline">
                support@hopenest.org
            </a>
        </p>
        <div class="flex justify-center space-x-6 mt-4">
            <a href="#" class="text-orange-600 hover:text-orange-800">
                <i class="fab fa-facebook text-xl"></i>
            </a>
            <a href="#" class="text-orange-600 hover:text-orange-800">
                <i class="fab fa-instagram text-xl"></i>
            </a>
            <a href="#" class="text-orange-600 hover:text-orange-800">
                <i class="fab fa-twitter text-xl"></i>
            </a>
        </div>
        <p class="text-xs text-gray-500 mt-6">
            © {{ date('Y') }} HopeNest ❤️ | All Rights Reserved<br>
            <a href="#" class="underline hover:text-orange-600">Unsubscribe</a> • 
            <a href="#" class="underline hover:text-orange-600">Privacy Policy</a>
        </p>
    </div>
</div>

</body>
</html>