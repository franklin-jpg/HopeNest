<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                            800: '#9a3412', 900: '#7c2d12',
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
                <div class="mx-auto h-24 w-24 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-hands-helping text-white text-4xl"></i>
                </div>
                <h1 class="mt-6 text-4xl font-bold text-gray-900">Your Volunteer Status</h1>
                <p class="mt-2 text-lg text-gray-600">Track your application and see your progress</p>
            </div>

            <!-- Status Card -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Application Status</h2>
                            <p class="text-orange-100">Submitted on {{ $volunteer->created_at->format('F j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($volunteer->status === 'pending')
                                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-yellow-400 text-yellow-900 shadow-lg">
                                    <i class="fas fa-clock mr-2"></i> Pending Review
                                </span>
                            @elseif($volunteer->status === 'approved')
                                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-green-500 text-white shadow-lg">
                                    <i class="fas fa-check-circle mr-2"></i> Approved!
                                </span>
                            @else
                                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-red-500 text-white shadow-lg">
                                    <i class="fas fa-times-circle mr-2"></i> {{ ucfirst($volunteer->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-8 py-10 space-y-8">
                    <!-- Quick Stats -->
                    @if($volunteer->isApproved())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-orange-50 rounded-xl p-6 text-center border-2 border-orange-200">
                            <i class="fas fa-trophy text-4xl text-orange-500 mb-3"></i>
                            <p class="text-3xl font-bold text-orange-600">{{ $volunteer->hours->where('status', 'approved')->sum('hours') }}</p>
                            <p class="text-gray-700 font-medium">Total Approved Hours</p>
                        </div>
                        <div class="bg-yellow-50 rounded-xl p-6 text-center border-2 border-yellow-200">
                            <i class="fas fa-hourglass-half text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-3xl font-bold text-yellow-700">{{ $volunteer->hours->where('status', 'pending')->sum('hours') }}</p>
                            <p class="text-gray-700 font-medium">Pending Hours</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-6 text-center border-2 border-blue-200">
                            <i class="fas fa-flag text-4xl text-blue-600 mb-3"></i>
                            <p class="text-3xl font-bold text-blue-700">{{ $volunteer->campaigns->where('pivot.status', 'active')->count() }}</p>
                            <p class="text-gray-700 font-medium">Active Campaigns</p>
                        </div>
                    </div>
                    @endif

                    <!-- Application Details -->
                    <div class="bg-gray-50 rounded-xl p-8 space-y-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-file-alt mr-3 text-orange-500"></i>
                            Your Application Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                            <div>
                                <p class="font-semibold text-orange-600">Phone</p>
                                <p>{{ $volunteer->phone }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-orange-600">Address</p>
                                <p>{{ $volunteer->address }}</p>
                            </div>
                            @if($volunteer->skills)
                            <div class="md:col-span-2">
                                <p class="font-semibold text-orange-600">Skills & Experience</p>
                                <p class="whitespace-pre-line">{{ $volunteer->skills }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-orange-600">Availability</p>
                                <p class="whitespace-pre-line">{{ $volunteer->availability }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-orange-600">Motivation</p>
                                <p class="whitespace-pre-line">{{ $volunteer->motivation }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        @if($volunteer->isApproved())
                            <a href="{{ route('volunteer.dashboard') }}"
                               class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-center flex items-center justify-center">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                Go to Volunteer Dashboard
                            </a>
                        @else
                            <button disabled class="flex-1 bg-gray-300 text-gray-600 font-bold py-4 px-8 rounded-xl cursor-not-allowed text-center">
                                <i class="fas fa-lock mr-3"></i>
                                Dashboard unlocks after approval
                            </button>
                        @endif

                        <a href="{{ route('volunteer.apply') }}"
                           class="flex-1 bg-white border-2 border-orange-500 text-orange-600 hover:bg-orange-50 font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-center flex items-center justify-center">
                            <i class="fas fa-edit mr-3"></i>
                            Edit Application
                        </a>
                    </div>

                    <!-- Help Text -->
                    @if(!$volunteer->isApproved())
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 text-center">
                        <i class="fas fa-info-circle text-4xl text-blue-500 mb-4"></i>
                        <p class="text-lg font-medium text-blue-900">
                            Your application is being reviewed by our team.
                        </p>
                        <p class="text-gray-700 mt-2">
                            We'll send you an email as soon as it's approved (usually within 24-48 hours).
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Session Messages (Success / Info / Error) -->
    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <span>{{ session('success') }}</span>
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
        // Auto-hide notifications
        setTimeout(() => {
            document.querySelectorAll('.animate-pulse').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>