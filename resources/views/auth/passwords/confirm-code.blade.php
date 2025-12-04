<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Code | HopeNest</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- iziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #fff7f0 0%, #ffe0c2 100%); }

        .gradient-bg { background: linear-gradient(135deg, #ff7e2f 0%, #ff5e3a 100%); }
        .animation-pulse-slow { animation: pulse-slow 3s infinite; }

        @keyframes pulse-slow { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }

        .input-focus { transition: all 0.3s ease; }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2); }

        .fade { transition: opacity 0.4s ease-in-out; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="flex flex-col md:flex-row w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- LEFT SIDE - Image & Info -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/2.jpg') }}" alt="HopeNest Reset" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-hands-helping text-4xl text-[#ff5722]"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-[#ff5722]">Confirm Your OTP</h1>
                <p class="text-xl mb-8">Enter the 6-digit code sent to your email</p>
            </div>

            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-2xl w-full max-w-md text-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <p>Confirm the 6 digit code sent to your email</p>
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
                    <p>OTP expires in 2 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE - OTP Form -->
    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ff5722;">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Enter Confirmation Code</h2>
            <p class="text-gray-600">We sent a 6-digit code to <span class="font-semibold">{{ $email ?? '' }}</span></p>
        </div>

        <!-- Success & Error Alerts -->
        @if(session('success'))
            <div id="alert-success" class="fade bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div id="alert-error" class="fade bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- OTP Form -->
        <form action="{{ route('user.verify-password-otp', ['token' => $token]) }}" method="POST" id="otpForm">
            @csrf
            <label for="otp" class="block text-gray-700 font-medium mb-1">6-Digit Code</label>
            <div class="relative">
                <input type="text" name="otp" id="otp" maxlength="6"
                       class="input-focus w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Enter code" required>
                <i class="fa-solid fa-lock text-gray-400 absolute right-3 top-3.5"></i>
            </div>
            @error('otp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="w-full mt-6 bg-[#ff5722] hover:bg-[#ff5722] text-white py-2 rounded-lg font-semibold flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i>
                Verify Code
            </button>
        </form>

        <!-- Countdown + Resend -->
        <div class="text-center mt-6">
            <p id="countdownText" class="text-sm text-gray-600"></p>
            <button id="resendBtn"
                    class="mt-3 text-indigo-600 font-semibold hover:underline disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <i class="fa-solid fa-rotate"></i> Resend Code
            </button>
        </div>

        <!-- Toast Message -->
        <div id="toast"
             class="fixed bottom-6 right-6 hidden bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg text-sm fade">
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const resendBtn = document.getElementById("resendBtn");
    const countdownText = document.getElementById("countdownText");
    const toast = document.getElementById("toast");

    // Safely parse server-sent expiry with fallback
    let expiresAt;
    try {
        const expiryData = @json($otpExpiresAt ?? null);
        if (expiryData) {
            const parsed = new Date(expiryData);
            expiresAt = isNaN(parsed.getTime()) ? new Date(Date.now() + 2*60*1000) : parsed;
        } else {
            expiresAt = new Date(Date.now() + 2*60*1000);
        }
    } catch { expiresAt = new Date(Date.now() + 2*60*1000); }

    let countdownInterval;
    function startCountdown() {
        clearInterval(countdownInterval);
        resendBtn.disabled = true;
        countdownInterval = setInterval(() => {
            const diff = expiresAt - new Date();
            if (diff <= 0) {
                clearInterval(countdownInterval);
                countdownText.textContent = "Didn't receive the code?";
                resendBtn.disabled = false;
                return;
            }
            const mins = Math.floor(diff / 60000);
            const secs = Math.floor((diff % 60000) / 1000);
            countdownText.textContent = `Resend available in ${mins}:${secs.toString().padStart(2,"0")}`;
        }, 1000);
    }
    startCountdown();

    resendBtn.addEventListener("click", async () => {
        resendBtn.disabled = true;
        resendBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Sending...`;
        try {
            const response = await fetch("{{ route('resend.code', ['token' => $token]) }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            });
            const data = await response.json();
            if (response.ok && data.success) {
                showToast(data.success, "success");
                const newExpiry = new Date(data.otpExpiresAt);
                expiresAt = isNaN(newExpiry.getTime()) ? new Date(Date.now() + 2*60*1000) : newExpiry;
                startCountdown();
            } else {
                showToast(data.error || "Failed to resend. Please try again.", "error");
                resendBtn.disabled = false;
            }
        } catch (err) {
            console.error("Resend error:", err);
            showToast("Network error. Try again.", "error");
            resendBtn.disabled = false;
        } finally {
            resendBtn.innerHTML = `<i class="fa-solid fa-rotate"></i> Resend Code`;
        }
    });

    function showToast(message, type = "success") {
        toast.textContent = message;
        toast.classList.remove("hidden", "bg-red-600", "bg-green-600");
        toast.classList.add(type === "success" ? "bg-green-600" : "bg-red-600");
        toast.style.opacity = "1";
        setTimeout(() => { toast.style.opacity = "0"; setTimeout(() => toast.classList.add("hidden"),400); }, 3000);
    }

    @if(session('success'))
        iziToast.success({ title: 'Success!', message: '{{ session('success') }}', position: 'topRight', timeout: 6000 });
    @endif
    @if(session('error'))
        iziToast.error({ title: 'Error', message: '{{ session('error') }}', position: 'topRight', timeout: 5000 });
    @endif
});
</script>
</body>
</html>
