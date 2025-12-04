@extends('layouts.app')

@section('title', 'Contact Us - HopeNest')

@section('content')

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
    }
    .contact-card {
        transition: all 0.3s ease;
    }
    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .input-group {
        position: relative;
    }
    .input-group input:focus + label,
    .input-group textarea:focus + label,
    .input-group select:focus + label,
    .input-group input:not(:placeholder-shown) + label,
    .input-group textarea:not(:placeholder-shown) + label {
        transform: translateY(-1.5rem) scale(0.85);
        color: #ff5722;
    }
    .floating-label {
        position: absolute;
        left: 1rem;
        top: 1rem;
        transition: all 0.3s ease;
        pointer-events: none;
        color: #9ca3af;
        background: white;
        padding: 0 0.25rem;
    }
</style>

<!-- Hero Section -->
<!-- Success Message -->
@if(session('success'))
<div class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 pointer-events-none">
    <div class="max-w-sm w-full bg-green-600 text-white rounded-lg shadow-2xl p-6 animate-bounce-in pointer-events-auto">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold">Message Sent Successfully!</h3>
                <p class="mt-1 text-green-50">
                    {{ session('success') }}
                </p>
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="ml-auto text-green-200 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
</div>

<!-- Add this CSS for the animation -->
<style>
    @keyframes bounceIn {
        0% { transform: translateY(-100px); opacity: 0; }
        60% { transform: translateY(30px); }
        80% { transform: translateY(-10px); }
        100% { transform: translateY(0); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounceIn 0.8s ease-out forwards;
    }
</style>
@endif
<section class="gradient-bg text-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 text-gray-300">Get In Touch</h1>
            <p class="text-xl md:text-2xl text-white/90 mb-8">
                We're here to help you make a difference. Reach out to us anytime.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('welcome') }}" class="bg-white text-orange-400 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all inline-flex items-center gap-2">
            
                    back to home
                </a>
                <a href="tel:+2348012345678" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-primary transition-all inline-flex items-center gap-2">
                    <i class="fas fa-phone"></i>
                    Call Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Cards -->
<section class="py-20 -mt-16 relative z-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            <!-- Phone Card -->
            <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-phone text-3xl text-orange-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Call Us</h3>
                <p class="text-gray-600 mb-4">Mon-Fri from 8am to 5pm</p>
                <a href="tel:+2348012345678" class="text-orange-400 font-semibold text-lg hover:underline">
                    +234 801 234 5678
                </a>
            </div>

            <!-- Email Card -->
            <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-envelope text-3xl text-orange-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Email Us</h3>
                <p class="text-gray-600 mb-4">We'll respond within 24 hours</p>
                <a href="mailto:support@hopenest.ng" class="text-orange-400 font-semibold text-lg hover:underline">
                    support@hopenest.ng
                </a>
            </div>

            <!-- Location Card -->
            <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-map-marker-alt text-3xl text-orange-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Visit Us</h3>
                <p class="text-gray-600  mb-4">Come say hello at our office</p>
                <p class="text-orange-400 font-semibold text-lg">
                    123 Hope Street, Benin City<br>Edo State, Nigeria
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Contact Form & Map Section -->
<section class="py-20 bg-white" id="contact-form">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-7xl mx-auto">
            
            <!-- Contact Form -->
            <div>
                <div class="mb-8">
                    <span class="text-orange-400 font-semibold text-sm uppercase tracking-wide">Contact Form</span>
                    <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Send Us a Message</h2>
                    <p class="text-gray-600 text-lg">
                        Have a question or need assistance? Fill out the form below and we'll get back to you as soon as possible.
                    </p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="input-group">
                            <input type="text" name="first_name" id="firstName" placeholder=" " required
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors"
                                value="{{ old('first_name') }}">
                            <label for="firstName" class="floating-label">First Name *</label>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="input-group">
                            <input type="text" name="last_name" id="lastName" placeholder=" " required
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors"
                                value="{{ old('last_name') }}">
                            <label for="lastName" class="floating-label">Last Name *</label>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder=" " required
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors"
                            value="{{ old('email') }}">
                        <label for="email" class="floating-label">Email Address *</label>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="input-group">
                        <input type="tel" name="phone" id="phone" placeholder=" "
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors"
                            value="{{ old('phone') }}">
                        <label for="phone" class="floating-label">Phone Number</label>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <select name="subject" id="subject" required
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors text-gray-700">
                            <option value="">Select a subject *</option>
                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="campaign" {{ old('subject') == 'campaign' ? 'selected' : '' }}>Campaign Support</option>
                            <option value="donation" {{ old('subject') == 'donation' ? 'selected' : '' }}>Donation Question</option>
                            <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                        </select>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="input-group">
                        <textarea name="message" id="message" rows="6" placeholder=" " required
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors resize-none">{{ old('message') }}</textarea>
                        <label for="message" class="floating-label">Your Message *</label>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-orange-400 text-white py-4 px-8 rounded-lg font-semibold text-lg hover:bg-secondary transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>

                    <p class="text-sm text-gray-500 text-center">
                        * Required fields
                    </p>
                </form>
            </div>

            <!-- Map & Info -->
            <div class="space-y-8">
                <!-- Map -->
                <div class="h-96 bg-gray-200 rounded-2xl overflow-hidden shadow-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.9287742563517!2d5.6037!3d6.3350!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMjAnMDYuMCJOIDXCsDM2JzEzLjMiRQ!5e0!3m2!1sen!2sng!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>

                <!-- Additional Info -->
                <div class="bg-gray-50 rounded-2xl p-8 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Office Hours</h3>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Monday - Friday</h4>
                            <p class="text-gray-900">8:00 AM - 5:00 PM WAT</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Saturday</h4>
                            <p class="text-gray-600">10:00 AM - 2:00 PM WAT</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-times-circle text-gray-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Sunday</h4>
                            <p class="text-gray-600">Closed</p>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">Follow Us</h3>
                    <p class="mb-6 text-white/90">Stay connected on social media</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 bg-orange-500 hover:bg-orange-400 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-orange-500 hover:bg-orange-400 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-orange-500 hover:bg-orange-400 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-orange-500 hover:bg-orange-400 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class=" text-orange-400 font-semibold text-sm uppercase tracking-wide">FAQ</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-600 text-lg">
                    Find quick answers to common questions
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="bg-white rounded-lg shadow-md overflow-hidden group">
                    <summary class="px-6 py-5 cursor-pointer font-semibold text-gray-800 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>How do I start a campaign?</span>
                        <i class="fas fa-chevron-down text-primary group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-600">
                        Simply register an account, click on "Start a Campaign," and follow our guided process. You'll need to provide campaign details, images, and your funding goal.
                    </div>
                </details>

                <!-- FAQ Item 2 -->
                <details class="bg-white rounded-lg shadow-md overflow-hidden group">
                    <summary class="px-6 py-5 cursor-pointer font-semibold text-gray-800 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>How long does it take to receive donations?</span>
                        <i class="fas fa-chevron-down text-primary group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-600">
                        Once your campaign is approved, you can start receiving donations immediately. Funds are processed within 3-5 business days.
                    </div>
                </details>

                <!-- FAQ Item 3 -->
                <details class="bg-white rounded-lg shadow-md overflow-hidden group">
                    <summary class="px-6 py-5 cursor-pointer font-semibold text-gray-800 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>Is there a fee for using HopeNest?</span>
                        <i class="fas fa-chevron-down text-primary group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-600">
                        We charge a small platform fee of 5% to cover operational costs. Payment processing fees may also apply.
                    </div>
                </details>

                <!-- FAQ Item 4 -->
                <details class="bg-white rounded-lg shadow-md overflow-hidden group">
                    <summary class="px-6 py-5 cursor-pointer font-semibold text-gray-800 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>Can I donate anonymously?</span>
                        <i class="fas fa-chevron-down text-primary group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-600">
                        Yes! When making a donation, simply check the "Donate Anonymously" option and your name will not be displayed publicly.
                    </div>
                </details>

                <!-- FAQ Item 5 -->
                <details class="bg-white rounded-lg shadow-md overflow-hidden group">
                    <summary class="px-6 py-5 cursor-pointer font-semibold text-gray-800 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>How do I know campaigns are legitimate?</span>
                        <i class="fas fa-chevron-down text-primary group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="px-6 pb-5 text-gray-600">
                        All campaigns go through a verification process. We review documents, verify identities, and monitor campaign progress to ensure transparency and legitimacy.
                    </div>
                </details>
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">Still have questions?</p>
                <a href="#contact-form" class="inline-flex items-center gap-2 bg-primary bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-secondary transition-all">
                    <i class="fas fa-envelope"></i>
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
</section>

@endsection