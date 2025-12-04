@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ config('app.url') }}/images/logo-white.png" alt="HopeNest" height="50">
        @endcomponent
    @endslot

    {{-- Hero Section --}}
    <div style="background: orange; padding: 40px 20px; text-align: center; color: white;">
        <h1 style="font-size: 32px; margin: 0;">Welcome to the HopeNest Family!</h1>
        <p style="font-size: 18px; margin: 16px 0 0;">{{ $name }}, thank you for joining us</p>
    </div>

    {{-- Body --}}
    @component('mail::message')
# You're officially part of something beautiful

Hi {{ $name }},

We’re so grateful you’ve chosen to stay updated with HopeNest.  
From now on you’ll be the first to know about:

- ✨ **New urgent campaigns** that need your help  
- ❤️ **Heartwarming success stories** from the families we support  
- 🎉 **Upcoming events & volunteer opportunities**  

**Every email we send is a chance to make a real difference — together.**

@component('mail::button', ['url' => url('/campaigns')])
See Active Campaigns
@endcomponent

With love and gratitude,<br>
**The HopeNest Team**

    @endcomponent

    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
            You're receiving this email because you subscribed at {{ config('app.url') }}.<br>
            Not interested anymore? 
            <a href="{{ route('newsletter.unsubscribe.form', ['email' => $email]) }}" 
               style="color: #ef4444; text-decoration: underline;">
                Click here to unsubscribe
            </a> — we'll be sad to see you go.
        @endcomponent
    @endslot

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} HopeNest. All rights reserved.<br>
            <small>Helping families in need · One heart at a time</small>
        @endcomponent
    @endslot
@endcomponent