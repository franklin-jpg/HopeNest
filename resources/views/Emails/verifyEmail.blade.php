<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your HopeNest OTP Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-lg mx-auto my-10 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Header with background image -->
        <div class="relative text-center py-10">
            <!-- Background Image -->
            <img src="{{ asset('images/slider/2.jpg') }}" 
                 alt="HopeNest - Feeding Hope" 
                 class="absolute inset-0 w-full h-full object-cover">

            <!-- Overlay -->
            <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.35);"></div>

            <!-- Header Content -->
            <div class="relative z-10">
                <h1 class="text-3xl font-bold tracking-wide" style="color: #ff5722;">HopeNest</h1>
                <p class="text-sm text-white opacity-90 mt-1">Secure OTP Verification</p>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8 text-gray-800">
            <p class="text-lg mb-4">Hi <span class="font-semibold">{{ $user->name ?? 'User' }}</span>,</p>

            <p class="mb-4">
                We received a request to verify your account on 
                <span class="font-semibold" style="color: #ff5722;">HopeNest</span>.  
                Please use the One-Time Password (OTP) below to complete your verification:
            </p>

            <!-- OTP Box -->
            <div class="text-center my-6">
                <div class="inline-block rounded-xl px-8 py-4 border-2"
                     style="border-color: #ff5722; background-color: rgba(255, 87, 34, 0.1);">
                    <span class="text-3xl font-extrabold tracking-widest" style="color: #ff5722;">
                        {{ $otp_code }}
                    </span>
                </div>
            </div>

            <p class="mb-4">
                This OTP is valid for the next <strong>2 minutes</strong>.  
                Please do not share it with anyone — 
                <span class="font-semibold">HopeNest</span> will never ask for your OTP.
            </p>

            <p>If you didn’t request this verification, simply ignore this message.</p>

            <div class="mt-8">
                <p>With warmth,</p>
                <p class="font-semibold" style="color: #ff5722;">The HopeNest Team</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-500 py-4">
            <p>
                Need help? Contact 
                <a href="mailto:support@hopenest.org" 
                   style="color: #ff5722;" 
                   class="font-medium hover:underline">
                   support@hopenest.org
                </a>
            </p>
            <p class="mt-1">&copy; {{ date('Y') }} HopeNest. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
