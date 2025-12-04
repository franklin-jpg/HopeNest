<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation - HopeNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #ff5722 0%, #ff8a50 100%);
        }
        
        .amount-card {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .amount-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 87, 34, 0.3);
        }
        
        .amount-card input:checked + .amount-label {
            background: linear-gradient(135deg, #ff5722 0%, #ff8a50 100%);
            color: white;
            border-color: #ff5722;
        }
        
        .payment-option {
            transition: all 0.2s ease;
        }
        
        .payment-option:hover {
            background-color: #f8f9ff;
        }
        
        .payment-option input:checked + .payment-label {
            background-color: #fff5f2;
            border-color: #ff5722;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #ff5722;
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
        }
        
        .donate-btn {
            background: linear-gradient(135deg, #ff5722 0%, #ff8a50 100%);
            transition: all 0.3s ease;
        }
        
        .donate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(255, 87, 34, 0.5);
        }
        
        .checkbox-custom:checked {
            background-color: #ff5722;
            border-color: #ff5722;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-8 px-4">

<div class="max-w-4xl mx-auto">
    
    <!-- Header Card -->
    <div class="gradient-bg rounded-2xl shadow-2xl p-8 mb-8 text-white">
        <div class="flex items-center justify-center mb-4">
            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                <i class="fas fa-heart text-4xl"></i>
            </div>
        </div>
        <h1 class="text-4xl font-bold text-center mb-2">Make a Difference Today</h1>
        <p class="text-center text-blue-100 text-lg">Your generous donation helps us continue our mission to build hope and transform lives</p>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
        
        <form action="{{ route('donations.process') }}" method="POST" id="donation-form">
            @csrf
            
            <!-- Hidden Fields -->
            <input type="hidden" name="campaign_id" value="{{ $campaign->id ?? 1 }}">
            <input type="hidden" name="agreed_to_terms" value="0" id="terms-hidden">
            <input type="hidden" name="currency" id="selected-currency" value="NGN">
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 m-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Donation Amount Section -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Select Your Donation Amount</h2>
                <p class="text-gray-600 mb-6">Choose a preset amount or enter your own</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                    @foreach ([1000, 2000, 3000, 5000, 10000] as $i => $amount)
                        <div class="amount-card">
                            <input 
                                id="donate-amount-{{ $i }}" 
                                name="amount" 
                                value="{{ $amount }}" 
                                type="radio"
                                class="hidden peer"
                                {{ old('amount') == $amount ? 'checked' : '' }}
                            >
                            <label for="donate-amount-{{ $i }}" class="amount-label cursor-pointer block border-2 border-gray-200 rounded-xl p-6 text-center">
                                <div class="text-2xl font-bold text-gray-800">₦{{ number_format($amount/1000) }}k</div>
                                <div class="text-xs text-gray-500 mt-1">{{ number_format($amount) }}</div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <!-- Custom Amount -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border-2 border-dashed border-orange-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Enter Custom Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold text-lg">₦</span>
                        <input 
                            id="other-amount"
                            name="custom_amount"
                            type="number"
                            placeholder="5,000"
                            min="100"
                            value="{{ old('custom_amount') }}"
                            class="form-input w-full border-2 border-gray-200 rounded-lg pl-10 pr-4 py-3 text-lg font-semibold @error('custom_amount') border-red-500 @enderror"
                        >
                    </div>
                    @error('custom_amount')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    @error('amount')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

          
           

<!-- Donor Information Section -->
<div class="p-8 border-b border-gray-100">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Information</h2>
    <p class="text-gray-600 mb-6">Help us stay connected with you</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
            <input 
                type="text"
                value="{{ auth()->user()->name }}"
                
                class=" form-input w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer"
            >
        </div>

        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <input 
                type="email"
                value="{{ auth()->user()->email }}"
                
                class=" form-input w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer"
            >
        </div>

        <!-- Phone (Editable) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
            <input 
                type="text"
                name="donor_phone"
                value="{{ old('donor_phone', auth()->user()->phone) }}"
                required
                placeholder="+234 800 000 0000"
                class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 @error('donor_phone') border-red-500 @enderror"
            >
            @error('donor_phone')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address (Optional) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Address (Optional)</label>
            <input 
                type="text"
                name="address"
                value="{{ old('address', auth()->user()->address) }}"
                class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3"
                placeholder="123 Main Street, Lagos"
            >
        </div>
    </div>
</div>

            <!-- Message Section -->
            <div class="p-8 border-b border-gray-100">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Leave a Message (Optional)
                </label>
                <textarea 
                    name="message" 
                    rows="4"
                    class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 resize-none @error('message') border-red-500 @enderror"
                    placeholder="Share why this cause matters to you..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Method Section -->
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Method</h2>
                <p class="text-gray-600 mb-6">Choose how you'd like to donate</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        'paypal' => ['label' => 'PayPal', 'icon' => 'fab fa-paypal', 'color' => 'blue'],
                        'credit' => ['label' => 'Credit Card', 'icon' => 'fas fa-credit-card', 'color' => 'purple'],
                        'debit' => ['label' => 'Debit Card', 'icon' => 'fas fa-credit-card', 'color' => 'indigo'],
                        'bank_transfer' => ['label' => 'Bank Transfer', 'icon' => 'fas fa-university', 'color' => 'green']
                    ] as $key => $details)
                        <div class="payment-option rounded-xl border-2 border-gray-200">
                            <input type="radio" id="payment-{{ $key }}" name="payment_method" value="{{ $key }}" required class="hidden peer" {{ old('payment_method') == $key ? 'checked' : '' }}>
                            <label for="payment-{{ $key }}" class="payment-label cursor-pointer flex items-center gap-4 p-4 rounded-xl">
                                <div class="w-12 h-12 bg-{{ $details['color'] }}-100 rounded-lg flex items-center justify-center">
                                    <i class="{{ $details['icon'] }} text-{{ $details['color'] }}-600 text-xl"></i>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $details['label'] }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options & Terms Section -->
            <div class="p-8 bg-gray-50 space-y-4">
                <!-- Anonymous -->
                <label class="flex items-start gap-3 cursor-pointer p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-orange-200 transition">
                    <input type="checkbox" name="is_anonymous" value="1" class="checkbox-custom w-5 h-5 rounded mt-0.5" {{ old('is_anonymous') ? 'checked' : '' }}>
                    <div>
                        <span class="font-semibold text-gray-800">Donate Anonymously</span>
                        <p class="text-sm text-gray-600 mt-1">Your name won't be displayed publicly</p>
                    </div>
                </label>

                <!-- Terms -->
                <label class="flex items-start gap-3 cursor-pointer p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-orange-200 transition @error('agreed_to_terms') border-red-500 @enderror">
                    <input type="checkbox" name="agreed_to_terms" value="1" required class="checkbox-custom w-5 h-5 rounded mt-0.5" id="terms-checkbox">
                    <div>
                        <span class="font-semibold text-gray-800">I agree to the Terms & Conditions *</span>
                        <p class="text-sm text-gray-600 mt-1">By donating, you agree to our terms of service and privacy policy</p>
                    </div>
                </label>
                @error('agreed_to_terms')
                    <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="p-8 bg-gradient-to-br from-gray-50 to-blue-50">
                <button 
                    type="submit" 
                    class="donate-btn w-full text-white px-8 py-5 font-bold text-lg rounded-xl shadow-lg"
                >
                    Complete Donation
                </button>
                
                <p class="text-center text-sm text-gray-500 mt-4">
                    Your payment information is secure and encrypted
                </p>
            </div>

        </form>

    </div>

    <!-- Footer Note -->
    <div class="text-center mt-8 text-gray-600">
        <p class="text-sm">
            100% of your donation goes directly to supporting our cause
        </p>
    </div>

</div>

</body>
</html>