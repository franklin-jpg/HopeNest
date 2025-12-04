<?php

namespace App\Http\Controllers\Auth\Paystack;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationReceiptMail;
use App\Notifications\NewDonationReceived;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

  public function process(Request $request)
{
    $validated = $request->validate([
        'campaign_id'    => 'required|exists:campaigns,id',
        'amount'         => 'required_without:custom_amount|numeric|min:500',
        'custom_amount'  => 'nullable|numeric|min:500',
        'donor_phone'    => 'required|string|max:20',
        'message'        => 'nullable|string|max:1000',
        'is_anonymous'   => 'nullable|boolean',
        'agreed_to_terms'=> 'required|accepted',
    ]);

    $amount = $request->filled('custom_amount') ? $request->custom_amount : $request->amount;
    $campaign = Campaign::findOrFail($request->campaign_id);

    // Minimum donation check
    if ($amount < $campaign->minimum_donation) {
        return back()->withErrors([
            'amount' => 'Minimum donation is ₦' . number_format($campaign->minimum_donation)
        ])->withInput();
    }

    $user = auth()->user(); 

    DB::beginTransaction();
    try {
        $donation = Donation::create([
            'campaign_id'   => $campaign->id,
            'user_id'       => $user->id,
            'donor_name'    => $user->name,
            'donor_email'   => $user->email,
            'donor_phone'   => $validated['donor_phone'],
            'amount'        => $amount,
            'currency'      => 'NGN',
            'message'       => $validated['message'] ?? null,
            'is_anonymous'  => $request->boolean('is_anonymous'),
            'agreed_to_terms' => true,
            'status'        => 'pending',
        ]);

        DB::commit();

        return $this->initializePaystackPayment($donation);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Donation failed: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
    }
}

    private function initializePaystackPayment(Donation $donation)
    {
        $paystackSecretKey = config('services.paystack.secret_key');
        
        if (empty($paystackSecretKey)) {
            Log::error('Paystack secret key not configured');
            return back()->withErrors(['error' => 'Payment gateway not configured. Please contact support.']);
        }
        
        $url = "https://api.paystack.co/transaction/initialize";
        
        // Paystack requires amount in smallest currency unit (kobo for NGN, cents for USD, etc.)
        $amountInSmallestUnit = $this->convertToSmallestUnit($donation->total_amount, $donation->currency);
        
        Log::info('Initializing Paystack payment', [
            'donation_id' => $donation->id,
            'amount' => $donation->total_amount,
            'currency' => $donation->currency,
            'amount_in_smallest_unit' => $amountInSmallestUnit,
            'reference' => $donation->payment_reference,
        ]);
        
        $fields = [
            'email' => $donation->donor_email,
            'amount' => $amountInSmallestUnit,
            'reference' => $donation->payment_reference,
            'currency' => $donation->currency,
            'callback_url' => route('donations.callback'),
            'metadata' => [
                'donation_id' => $donation->id,
                'campaign_id' => $donation->campaign_id,
                'donor_name' => $donation->donor_name,
                'donor_phone' => $donation->donor_phone,
                'custom_fields' => [
                    [
                        'display_name' => 'Donation ID',
                        'variable_name' => 'donation_id',
                        'value' => $donation->id
                    ],
                    [
                        'display_name' => 'Campaign',
                        'variable_name' => 'campaign',
                        'value' => $donation->campaign->title
                    ]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $paystackSecretKey",
            "Content-Type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            Log::error('Paystack cURL error: ' . $curlError);
            return back()->withErrors(['error' => 'Connection error. Please try again.']);
        }

        $result = json_decode($result, true);
        
        Log::info('Paystack response', [
            'http_code' => $httpCode,
            'response' => $result,
        ]);

        if ($result && isset($result['status']) && $result['status'] === true) {
            $donation->update(['paystack_reference' => $result['data']['reference']]);
            return redirect($result['data']['authorization_url']);
        }

        // Log the error details
        $errorMessage = $result['message'] ?? 'Unknown error';
        Log::error('Paystack initialization failed', [
            'error' => $errorMessage,
            'full_response' => $result,
        ]);

        return back()->withErrors(['error' => 'Payment initialization failed: ' . $errorMessage])->withInput();
    }

    private function convertToSmallestUnit(float $amount, string $currency): int
    {
        // Currencies with 2 decimal places (cents, kobo, etc.)
        $standardCurrencies = ['NGN', 'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'ZAR', 'KES', 'GHS'];
        
        if (in_array($currency, $standardCurrencies)) {
            $result = (int) round($amount * 100);
            Log::info('Converting to smallest unit', [
                'amount' => $amount,
                'currency' => $currency,
                'result' => $result,
            ]);
            return $result;
        }
        
        return (int) $amount;
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('welcome')->with('error', 'Invalid payment reference');
        }

        $paystackSecretKey = config('services.paystack.secret_key');
        $url = "https://api.paystack.co/transaction/verify/$reference";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $paystackSecretKey",
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        if ($result && $result['status'] === true && $result['data']['status'] === 'success') {
            $donation = Donation::where('payment_reference', $reference)->firstOrFail();
            
             // Notify admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new NewDonationReceived($donation));
    }
            DB::beginTransaction();
            try {
                $donation->update([
                    'status' => 'successful',
                    'paid_at' => now(),
                    'transaction_id' => $result['data']['id'],
                    'payment_method' => $result['data']['channel'],
                ]);

                // Update campaign raised amount (use base currency amount)
                $donation->campaign->increment('raised_amount', $donation->amount_in_base_currency);

                DB::commit();

                try {
                    Mail::to($donation->donor_email)->send(new DonationReceiptMail($donation));
                } catch (\Exception $e) {
                    Log::error('Failed to send donation receipt: ' . $e->getMessage());
                }

                return redirect()->route('donations.success', $donation->id);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment verification failed: ' . $e->getMessage());
                return redirect()->route('welcome')->with('error', 'Payment verification failed');
            }
        }

        $donation = Donation::where('payment_reference', $reference)->first();
        if ($donation) {
            $donation->update(['status' => 'failed']);
        }

        return redirect()->route('donations.failed', $reference);
    }

    public function success($id)
    {
        $donation = Donation::with(['campaign', 'user'])->findOrFail($id);
        
        // Ensure user can only view their own donation
        if (!auth()->check() || (auth()->id() !== $donation->user_id && $donation->user_id !== null)) {
            // Allow viewing if guest donation matches email in session
            if (!session()->has('donation_email') || session('donation_email') !== $donation->donor_email) {
                abort(403);
            }
        }

        return view('donations.success', compact('donation'));
    }

    public function failed($reference)
    {
        $donation = Donation::where('payment_reference', $reference)->first();
        return view('donations.failed', compact('donation'));
    }

    public function downloadReceipt($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);
        
        $pdf = Pdf::loadView('Donations.receipt-pdf', compact('donation'));
        return $pdf->download("receipt-{$donation->receipt_number}.pdf");
    }
}