<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email || HOPENEST</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
</head>

<body class="min-h-screen flex items-center justify-center p-4 bg-gray-100">

<div class="flex flex-col md:flex-row w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- Left Side - Image -->
    <div class="md:w-1/2 relative hidden md:block">
        <img src="{{ asset('images/slider/2.jpg') }}" alt="HopeNest mission" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.35);"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-10">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4 text-[#ff5722]">Confirm Your Email</h1>
                <p class="text-xl mb-8">Thank you for joining <span class="font-semibold text-[#ff5722]">HopeNest</span> — verify your email to continue making a difference.</p>
            </div>

            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-6 rounded-2xl w-full max-w-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-lock"></i>
                    </div>
                    <p>Safe & private confirmation</p>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p>Code valid for 2 minutes</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-[#ff5722] bg-opacity-80">
                        <i class="fas fa-redo"></i>
                    </div>
                    <p>Request new code anytime</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#ff5722;">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Verify Your Email</h2>
            <p class="text-gray-600">Verification link sent to:</p>
            <p id="user-email" class="font-semibold text-gray-800 mt-1">{{ $email }}</p>
        </div>

        <form action="{{ route('user.verify-otp', $token) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4 text-center">Enter your 6-digit code</label>
                <input type="text" name="otp"
                    class="pl-10 pr-10 block w-full rounded-lg border-gray-300 bg-gray-50 py-3 px-4 focus:border-[#ff5722] focus:ring-[#ff5722]"
                    placeholder="Enter OTP">
            </div>

            <div class="text-center">
                <div id="timer" class="text-sm text-gray-600 mb-4">
                    <i class="fas fa-clock mr-1"></i>
                    Code expires in: <span id="countdown" class="font-semibold text-[#ff5722]">2:00</span>
                </div>
            </div>

            <button type="submit"
                class="w-full text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center"
                style="background:#ff5722;">
                <span>Verify Code</span>
                <i class="fas fa-check ml-2"></i>
            </button>

            <div class="text-center">
                <p class="text-gray-600 text-sm mb-3">Didn't get the code?</p>
                <button type="button" id="resendBtn" class="font-medium text-sm text-[#ff5722] hover:underline">
                    <i class="fas fa-redo mr-1"></i> Resend Code
                </button>
            </div>
        </form>

        <div class="mt-8 text-center border-t border-gray-200 pt-6">
            <p class="text-gray-600 text-sm">
                Wrong email?
                <a href="{{ route('register') }}" class="font-medium text-[#ff5722] hover:underline">
                    Go back to registration
                </a>
            </p>
        </div>
    </div>
</div>

<style>
.gradient-bg {
    background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let timerInterval;
    let expiresAt;
    const countdownElement = document.getElementById("countdown");
    const resendBtn = document.getElementById("resendBtn");

    // Mask email function
    function maskEmail(email) {
        if (!email || typeof email !== 'string' || !email.includes('@')) {
            return email || '';
        }
        
        const [local, domain] = email.split('@');
        
        if (!local || !domain) {
            return email;
        }
        
        const visiblePart = local.slice(0, 1);
        const maskedPart = '*'.repeat(Math.max(local.length - 1, 1));
        return `${visiblePart}${maskedPart}@${domain}`;
    }

    // Actually mask the email on page load
    const emailElement = document.getElementById("user-email");
    if (emailElement) {
        const actualEmail = emailElement.textContent.trim();
        emailElement.textContent = maskEmail(actualEmail);
    }

    // Countdown logic
    function startCountdown(expiryMs) {
        expiresAt = expiryMs;
        if (timerInterval) clearInterval(timerInterval);

        function updateCountdown() {
            const now = Date.now();
            const distance = expiresAt - now;

            if (distance <= 0) {
                countdownElement.innerHTML = "Expired";
                clearInterval(timerInterval);
                resendBtn.disabled = false;
                resendBtn.classList.remove("opacity-50", "cursor-not-allowed");
                return;
            }

            const minutes = Math.floor(distance / 1000 / 60);
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            countdownElement.innerHTML = `${minutes}:${String(seconds).padStart(2, '0')}`;
        }

        resendBtn.disabled = true;
        resendBtn.classList.add("opacity-50", "cursor-not-allowed");
        updateCountdown();
        timerInterval = setInterval(updateCountdown, 1000);
    }

    // Initialize countdown from server 
    const expiresAtRaw = @json($otpExpiresAt);
    const initialExpiry = expiresAtRaw ? Date.parse(expiresAtRaw) : NaN;
    if (!expiresAtRaw || isNaN(initialExpiry)) {
        countdownElement.innerHTML = "Expired";
        resendBtn.disabled = false;
    } else {
        startCountdown(initialExpiry);
    }

    // Resend OTP logic 
    resendBtn.addEventListener("click", function () {
        resendBtn.disabled = true;
        resendBtn.classList.add("opacity-50", "cursor-not-allowed");

        fetch("{{ route('user.resend-otp', $token) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({})
        })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || "Failed to resend OTP.");
            }

            iziToast.success({
                title: 'Success',
                message: data.success,
                position: 'topRight'
            });

            if (data.otpExpiresAt) {
                const newExpiry = Date.parse(data.otpExpiresAt);
                startCountdown(newExpiry);
            }
        })
        .catch(error => {
            iziToast.error({
                title: 'Error',
                message: error.message || "Something went wrong. Try again.",
                position: 'topRight'
            });
            resendBtn.disabled = false;
            resendBtn.classList.remove("opacity-50", "cursor-not-allowed");
        });
    });
});
</script>

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
                    animateInside: true,
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
                    animateInside: true,
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
                    animateInside: true,
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
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @endif
        });
    </script>
@endif
 
</body>
</html>