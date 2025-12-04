@component('mail::message')
{{-- Header --}}
@component('mail::header')
    <img src="{{ asset('images/hopenest-logo.png') }}" alt="Hopenest NGO" style="height: 60px;">
@endcomponent

{{-- Greeting --}}
# Dear {{ $donorName }},

@if($isRecurring)
Thank you from the bottom of our hearts for becoming a **monthly supporter** of Hopenest! Your recurring gift of **{{ $amount}} {{ $frequency }}** will create lasting change.
@else
Thank you so much for your generous donation of **{{ $amount }}** to **{{ $campaignTitle }}**! Because of you, hope is rising.
@endif

@component('mail::panel')
    <div style="text-align: center;">
        <h2 style="color: #16a34a; margin: 0;">
            @if($isRecurring)
                Welcome to the Hopenest Family
            @else
                Thank You for Standing with Us
            @endif
        </h2>
        <p style="font-size: 18px; color: #333; margin: 16px 0;">
            Your support helps us bring clean water, education, healthcare, and hope to communities in need.
        </p>
    </div>
@endcomponent

@component('mail::table')
| Description                  | Details
|-----------------------------|--------------------------------------------------|
| **Donation Amount**         | {{ $amount }}
| **Campaign**                | {{ $campaignTitle }}
| **Donation Type**           | {{ $frequency }} @if($isRecurring) Recurring @endif
| **Date**                    | {{ $donation->paid_at?->format('F j, Y \a\t g:i A') ?? now()->format('F j, Y') }}
| **Receipt Number**          | `{{ $donation->receipt_number }}`
@if($isRecurring)
| **Next Billing Date**       | {{ $nextBilling }}
@endif
@endcomponent

@if($donation->message)
@component('mail::panel')
    <strong>Your Message to Us:</strong><br>
    <em>"{{ $donation->message }}"</em>
    <br><br>
    <small>— We read every single one. Thank you for these kind words.</small>
@endcomponent
@endif

@component('mail::button', ['url' => route('donations.receipt', $donation->receipt_number)])
    View Your Receipt
@endcomponent

@if($isRecurring)
<p style="text-align: center; margin-top: 30px;">
    You can manage or update your recurring donation anytime here:<br>
    @component('mail::button', ['url' => route('donor.dashboard')])
        Manage My Subscription
    @endcomponent
</p>
@endif

With deepest gratitude,<br>
**The Hopenest Team**  
Changing lives, one donation at a time

@component('mail::subcopy')
If you have any questions, reply to this email or contact us at <a href="mailto:support@hopenest.org">support@hopenest.org</a><br>
Hopenest is a registered non-profit organization. All donations are tax-deductible where applicable.
@endcomponent

@component('mail::footer')
    © {{ date('Y') }} Hopenest NGO. All rights reserved.<br>
    <a href="{{ url('/') }}">{{ url('/') }}</a>
@endcomponent
@endcomponent