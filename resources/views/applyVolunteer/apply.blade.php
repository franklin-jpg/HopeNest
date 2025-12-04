<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-orange-50 via-amber-50 to-orange-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-24 w-24 bg-orange-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-hands-helping text-white text-4xl"></i>
                </div>
                <h2 class="mt-6 text-4xl font-bold text-gray-900">Join Our Volunteer Team</h2>
                <p class="mt-2 text-lg text-gray-600 max-w-2xl mx-auto">
                    Make a difference in your community! Share your time, skills, and passion with us.
                </p>
            </div>

            <!-- Form Card -->
            <div class="max-w-3xl mx-auto">
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-8 py-8 sm:px-12 sm:py-10">
                        <form method="POST" action="{{ route('volunteer.store') }}" class="space-y-8">
                            @csrf
                            
                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-2 text-orange-500"></i>
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        name="phone" 
                                        required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('phone') border-red-500 @enderror"
                                        placeholder="Enter your phone number"
                                        value="{{ old('phone') }}"
                                    >
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="address" 
                                        required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('address') border-red-500 @enderror"
                                        placeholder="Your full address"
                                        value="{{ old('address') }}"
                                    >
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Skills -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tools mr-2 text-orange-500"></i>
                                    Skills & Experience
                                </label>
                                <textarea 
                                    name="skills" 
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 resize-none"
                                    placeholder="Tell us about your skills, experience, or special talents (e.g., photography, event planning, teaching, etc.)"
                                >{{ old('skills') }}</textarea>
                            </div>

                            <!-- Availability -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-orange-500"></i>
                                    Availability <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="availability" 
                                    required
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 resize-none @error('availability') border-red-500 @enderror"
                                    placeholder="When are you available? (e.g., Weekends, evenings, specific days, etc.)"
                                >{{ old('availability') }}</textarea>
                                @error('availability')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Motivation -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-heart mr-2 text-orange-500"></i>
                                    Why do you want to volunteer? <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="motivation" 
                                    required
                                    rows="5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 resize-none @error('motivation') border-red-500 @enderror"
                                    placeholder="Tell us why you're passionate about volunteering with us and what you hope to achieve..."
                                >{{ old('motivation') }}</textarea>
                                @error('motivation')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button 
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center"
                                >
                                    <i class="fas fa-paper-plane mr-3"></i>
                                    Submit My Application
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center text-white">
                                <i class="fas fa-shield-alt text-orange-200 mr-3 text-xl"></i>
                                <p class="text-sm font-medium">Your information is secure and will only be used for volunteer coordination</p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex items-center justify-center sm:justify-end">
                                <a href="{{ route('volunteer.status') }}" class="inline-flex items-center text-orange-100 hover:text-white text-sm font-medium transition-colors duration-300">
                                    <i class="fas fa-eye mr-2"></i>
                                    Check Application Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-4 rounded-xl shadow-lg animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-2xl mr-3"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Auto-hide notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.animate-pulse');
            notifications.forEach(notification => {
                notification.style.transition = 'opacity 0.5s ease-out';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            });
        }, 5000);

        // Form focus animation
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-orange-200');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-orange-200');
            });
        });
    </script>
</body>
</html>