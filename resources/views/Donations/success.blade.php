<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Donation! - HopeNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fff8f5 0%, #fff0eb 50%, #ffe5d9 100%);
            min-height: 100vh;
        }

        .gradient-header {
            background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
        }

        .success-icon-circle {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .receipt-card {
            box-shadow: 0 20px 40px -10px rgba(255, 87, 34, 0.15);
            transition: all 0.3s ease;
        }

        .receipt-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(255, 87, 34, 0.25);
        }

        .detail-row {
            transition: background 0.2s ease;
        }

        .detail-row:hover {
            background-color: #fff8f5;
        }

        .highlight-row {
            background: linear-gradient(135deg, #ffeae2 0%, #fff0eb 100%);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #ff5722 0%, #ff8a50 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 87, 34, 0.4);
        }

        .btn-outline-orange {
            border: 2px solid #ff5722;
            color: #ff5722;
            transition: all 0.3s ease;
        }

        .btn-outline-orange:hover {
            background: #ff5722;
            color: white;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
        }

        .social-btn:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .impact-box {
            border-left: 5px solid #ff5722;
            background: linear-gradient(to right, #fff8f5, #ffffff);
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">

<div class="max-w-4xl mx-auto">

    <!-- Success Header -->
    <div class="gradient-header rounded-2xl shadow-2xl p-10 mb-8 text-white text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 success-icon-circle rounded-full mb-6">
            <i class="fas fa-check text-6xl animate-pulse"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Thank You!</h1>
        <p class="text-xl md:text-2xl opacity-95">Your generous donation has been received</p>
        <p class="text-lg mt-2 opacity-90">You're making a real difference in someone's life today ❤️</p>
    </div>

    <!-- Receipt Card -->
    <div class="receipt-card bg-white rounded-2xl overflow-hidden">
        
        <!-- Card Header -->
        <div class="gradient-header p-6 text-white">
            <h3 class="text-2xl font-bold flex items-center gap-3">
                <i class="fas fa-file-invoice-dollar text-3xl"></i>
                Donation Receipt
            </h3>
        </div>

        <!-- Receipt Details -->
        <div class="p-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Receipt No.</span>
                    <div class="font-bold text-lg text-orange-600">#{{ $donation->receipt_number }}</div>
                </div>
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Transaction ID</span>
                    <div class="font-mono text-sm bg-gray-100 px-3 py-1 rounded">{{ $donation->paystack_reference }}</div>
                </div>
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Date & Time</span>
                    <div class="font-semibold">{{ $donation->paid_at->format('M d, Y \a\t g:i A') }}</div>
                </div>
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Campaign</span>
                    <div class="font-semibold text-orange-600">{{ $donation->campaign->title }}</div>
                </div>
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Donor</span>
                    <div class="font-semibold capitalize">
    @if($donation->is_anonymous)
        <span class="italic text-gray-600">Anonymous Donor</span>
    @else
        {{ $donation->donor_name }}
    @endif
</div>
                </div>
                <div class="detail-row py-3 px-4 rounded-lg">
                    <span class="text-gray-500 font-medium">Payment Method</span>
                    <div class="font-semibold capitalize">{{ str_replace('_', ' ', $donation->payment_method) }}</div>
                </div>
            </div>

            <!-- Highlighted Amount -->
            <div class="highlight-row mt-6 p-6 rounded-2xl border-2 border-dashed border-orange-300 text-center">
                <p class="text-gray-600 text-sm mb-1">Amount Donated</p>
                <div class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">
                    {{ $donation->formatted_amount }}
                </div>
                @if($donation->cover_fee)
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-check-circle text-green-600 mr-1"></i>
                    You covered the processing fee of ₦{{ number_format($donation->processing_fee, 2) }} — Thank you!
                </p>
                @endif
            </div>

            <!-- Impact Message -->
            <div class="impact-box mt-8 p-6 rounded-xl">
                <h4 class="text-2xl font-bold text-gray-800 mb-3 flex items-center gap-3">
                    <i class="fas fa-heart text-red-600 text-3xl"></i> Your Impact
                </h4>
                <p class="text-gray-700 leading-relaxed text-lg">
                    {{ $donation->campaign->custom_thank_you ?? 'Your donation brings hope, transforms lives, and helps us continue our mission to build a better tomorrow. Every naira counts — thank you for believing in our cause.' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gradient-to-br from-gray-50 to-orange-50 p-8 border-t border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <a href="{{ route('donations.receipt', $donation->id) }}" 
                   class="btn-gradient text-white font-bold py-4 px-8 rounded-xl flex items-center justify-center gap-3 text-lg shadow-lg">
                    <i class="fas fa-download"></i> Download PDF Receipt
                </a>
                <button onclick="emailReceipt()" 
                        class="btn-outline-orange font-bold py-4 px-8 rounded-xl flex items-center justify-center gap-3 text-lg">
                    <i class="fas fa-envelope"></i> Email Receipt
                </button>
            </div>

            <!-- Share Your Impact -->
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-700 mb-4">Share your kindness with the world</p>
                <div class="flex justify-center gap-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('show.single', $donation->campaign->slug)) }}" 
                       target="_blank" class="social-btn bg-[#3b5998] shadow-lg">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=I%20just%20supported%20{{ urlencode($donation->campaign->title) }}%20on%20HopeNest!%20{{ urlencode(route('show.single', $donation->campaign->slug)) }}" 
                       target="_blank" class="social-btn bg-[#1da1f2] shadow-lg">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('show.single', $donation->campaign->slug)) }}" 
                       target="_blank" class="social-btn bg-[#0077b5] shadow-lg">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://wa.me/?text=I%20just%20donated%20to%20{{ urlencode($donation->campaign->title) }}%20on%20HopeNest!%20{{ route('show.single', $donation->campaign->slug) }}" 
                       target="_blank" class="social-btn bg-[#25d366] shadow-lg">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Additional Actions -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4 text-center">
                <a href="{{ route('show.single', $donation->campaign->slug) }}" 
                   class="btn-outline-orange py-4 px-6 rounded-xl font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-eye"></i> View Campaign Updates
                </a>
                <a href="{{ route('all.campaigns') }}" 
                   class="bg-orange-100 text-orange-700 font-bold py-4 px-6 rounded-xl hover:bg-orange-200 transition flex items-center justify-center gap-2">
                    <i class="fas fa-heart"></i> Support Another Cause
                </a>
            </div>
        </div>
    </div>

    <!-- What's Next -->
    <div class="mt-10 bg-white rounded-2xl shadow-xl p-8 border-l-4 border-orange-500">
        <h4 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <i class="fas fa-info-circle text-orange-600"></i> What Happens Next?
        </h4>
        <ul class="space-y-4 text-gray-700">
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                <span>You’ll receive a confirmation email with your receipt shortly</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                <span>Your donation will be processed and allocated within 24 hours</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                <span>You can track this campaign’s progress anytime</span>
            </li>
            @if($donation->frequency !== 'one-time')
            <li class="flex items-start gap-3">
                <i class="fas fa-sync text-orange-600 mt-1"></i>
                <span>Your <strong>{{ ucfirst(str_replace('-', ' ', $donation->frequency)) }}</strong> donation will continue automatically</span>
            </li>
            @endif
        </ul>
    </div>

    <!-- Footer Note -->
    <div class="text-center mt-10 text-gray-600">
        <p class="text-sm flex items-center justify-center gap-2">
            <i class="fas fa-shield-alt text-orange-600"></i>
            100% secure • Tax-deductible • Transparent
        </p>
        <p class="mt-4 text-2xl">🙏 Thank you for being a hero</p>
    </div>
</div>

<script>
function emailReceipt() {
    // In real app, trigger backend email
    alert('Receipt has been sent to {{ $donation->donor_email }} 📧');
}
</script>

</body>
</html>