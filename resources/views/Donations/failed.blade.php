@extends('layouts.app')

@section('content')
<div class="donation-failed-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Failed Message -->
                <div class="failed-header text-center mb-5">
                    <div class="failed-icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <h1>Payment Unsuccessful</h1>
                    <p class="lead">We couldn't process your donation</p>
                </div>

                <!-- Error Card -->
                <div class="error-card card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0"><i class="fa fa-exclamation-triangle"></i> Transaction Failed</h3>
                    </div>
                    <div class="card-body">
                        @if($donation)
                        <div class="error-details">
                            <div class="detail-row">
                                <span class="label">Reference Number:</span>
                                <span class="value">{{ $donation->payment_reference }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Campaign:</span>
                                <span class="value">{{ $donation->campaign->title }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Amount Attempted:</span>
                                <span class="value">{{ $donation->formatted_amount }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Date & Time:</span>
                                <span class="value">{{ $donation->created_at->format('F d, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Common Reasons -->
                        <div class="common-reasons mt-4 p-3 bg-light rounded">
                            <h4><i class="fa fa-info-circle"></i> Common Reasons for Failed Payments</h4>
                            <ul class="reasons-list">
                                <li><i class="fa fa-circle-o"></i> Insufficient funds in your account</li>
                                <li><i class="fa fa-circle-o"></i> Incorrect card details or expired card</li>
                                <li><i class="fa fa-circle-o"></i> Card not enabled for online transactions</li>
                                <li><i class="fa fa-circle-o"></i> Network or connectivity issues</li>
                                <li><i class="fa fa-circle-o"></i> Transaction limit exceeded</li>
                                <li><i class="fa fa-circle-o"></i> Card blocked or restricted by your bank</li>
                            </ul>
                        </div>

                        <!-- What to Do Next -->
                        <div class="next-steps mt-4 p-3 border rounded">
                            <h4><i class="fa fa-lightbulb-o"></i> What You Can Do</h4>
                            <div class="steps">
                                <div class="step-item">
                                    <span class="step-number">1</span>
                                    <div class="step-content">
                                        <strong>Check Your Card Details</strong>
                                        <p>Ensure your card number, expiry date, and CVV are correct</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">2</span>
                                    <div class="step-content">
                                        <strong>Verify Your Balance</strong>
                                        <p>Make sure you have sufficient funds in your account</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">3</span>
                                    <div class="step-content">
                                        <strong>Contact Your Bank</strong>
                                        <p>Your bank may need to enable online transactions or lift restrictions</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">4</span>
                                    <div class="step-content">
                                        <strong>Try Again</strong>
                                        <p>Once resolved, you can retry your donation</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-footer bg-transparent">
                        <div class="action-buttons text-center">
                            @if($donation)
                            <a href="{{ route('show.single', $donation->campaign->slug) }}" class="btn btn-primary btn-lg">
                                <i class="fa fa-repeat"></i> Try Again
                            </a>
                            @endif
                            <a href="{{ route('all.campaigns') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa fa-search"></i> Browse Other Campaigns
                            </a>
                        </div>

                        <!-- Alternative Payment Methods -->
                        <div class="alternative-methods mt-4">
                            <h5 class="text-center mb-3">Alternative Payment Options</h5>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="payment-option">
                                        <i class="fa fa-university fa-3x text-primary mb-2"></i>
                                        <h6>Bank Transfer</h6>
                                        <p class="small">Use a different payment method on retry</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-option">
                                        <i class="fa fa-mobile fa-3x text-success mb-2"></i>
                                        <h6>USSD</h6>
                                        <p class="small">Pay via your mobile phone</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-option">
                                        <i class="fa fa-credit-card fa-3x text-info mb-2"></i>
                                        <h6>Different Card</h6>
                                        <p class="small">Try using another card</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Section -->
                <div class="support-section mt-4 card">
                    <div class="card-body text-center">
                        <h4><i class="fa fa-support"></i> Need Help?</h4>
                        <p>If you continue to experience issues or have questions about your donation, our support team is here to assist you.</p>
                        
                        <div class="contact-options mt-3">
                            <a href="mailto:support@hopenest.com" class="btn btn-outline-primary">
                                <i class="fa fa-envelope"></i> Email Support
                            </a>
                            <a href="tel:+2348012345678" class="btn btn-outline-primary">
                                <i class="fa fa-phone"></i> Call Us
                            </a>
                            <a href="#" class="btn btn-outline-primary" onclick="openLiveChat()">
                                <i class="fa fa-comments"></i> Live Chat
                            </a>
                        </div>

                        <div class="contact-info mt-4">
                            <p class="mb-1"><strong>Email:</strong> support@hopenest.com</p>
                            <p class="mb-1"><strong>Phone:</strong> +234 801 234 5678</p>
                            <p class="mb-1"><strong>Hours:</strong> Mon-Fri, 8:00 AM - 6:00 PM WAT</p>
                        </div>
                    </div>
                </div>

                <!-- Reassurance -->
                <div class="reassurance-section mt-4 p-4 bg-light rounded text-center">
                    <i class="fa fa-shield fa-3x text-success mb-3"></i>
                    <h5>Your Security is Our Priority</h5>
                    <p class="mb-0">
                        No charges were made to your account. All payment information is encrypted and secure. 
                        We use industry-standard security measures to protect your financial data.
                    </p>
                </div>

                <!-- Return Home -->
                <div class="text-center mt-4 mb-5">
                    <a href="{{ route('welcome') }}" class="btn btn-link">
                        <i class="fa fa-home"></i> Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.donation-failed-page {
    padding: 60px 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.failed-icon {
    font-size: 80px;
    color: #dc3545;
    margin-bottom: 20px;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.error-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}

.error-details .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.error-details .detail-row:last-child {
    border-bottom: none;
}

.reasons-list {
    list-style: none;
    padding-left: 0;
}

.reasons-list li {
    padding: 8px 0;
    padding-left: 25px;
    position: relative;
}

.reasons-list li i {
    position: absolute;
    left: 0;
    top: 12px;
    color: #dc3545;
}

.next-steps .steps {
    margin-top: 20px;
}

.step-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s;
}

.step-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.step-number {
    min-width: 40px;
    height: 40px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.step-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.action-buttons .btn {
    margin: 5px;
    min-width: 200px;
}

.payment-option {
    padding: 20px;
    border: 1px solid #eee;
    border-radius: 8px;
    transition: all 0.3s;
    height: 100%;
}

.payment-option:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0,123,255,0.1);
    transform: translateY(-2px);
}

.payment-option i {
    transition: all 0.3s;
}

.payment-option:hover i {
    transform: scale(1.1);
}

.contact-options a {
    margin: 5px;
}

.contact-info {
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.support-section {
    border-left: 4px solid #007bff;
}

.reassurance-section {
    border: 2px dashed #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    .failed-icon {
        font-size: 60px;
    }
    
    .action-buttons .btn {
        display: block;
        width: 100%;
        margin: 10px 0;
    }
    
    .payment-option {
        margin-bottom: 15px;
    }
    
    .contact-options a {
        display: block;
        margin: 10px 0;
    }
}
</style>

<script>
function openLiveChat() {
    // Integrate with your live chat system (e.g., Tawk.to, Intercom, etc.)
    alert('Live chat will open here. Please integrate your preferred chat system.');
    // Example for Tawk.to:
    // Tawk_API.toggle();
}

// Auto-scroll to top on page load
window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});

// Optional: Track failed payment for analytics
@if($donation)
if (typeof gtag !== 'undefined') {
    gtag('event', 'donation_failed', {
        'event_category': 'donations',
        'event_label': '{{ $donation->campaign->title }}',
        'value': {{ $donation->amount }}
    });
}
@endif
</script>
@endsection