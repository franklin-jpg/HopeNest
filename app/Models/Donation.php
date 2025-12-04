<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Donation extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'is_anonymous',
        'amount',
        'currency',
        'exchange_rate',
        'amount_in_base_currency',
        'frequency',
        'message',
        'cover_fee',
        'processing_fee',
        'total_amount',
        'payment_method',
        'payment_reference',
        'paystack_reference',
        'transaction_id',
        'status',
        'paid_at',
        'receipt_number',
        'agreed_to_terms',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'amount_in_base_currency' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'cover_fee' => 'boolean',
        'agreed_to_terms' => 'boolean',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();


   
        
        static::creating(function ($donation) {
            $currencyService = app(CurrencyService::class);

            // Generate unique payment reference
            if (empty($donation->payment_reference)) {
                $donation->payment_reference = 'DN-' . strtoupper(Str::random(12));
            }

            // Set default currency if not provided
            if (empty($donation->currency)) {
                $donation->currency = 'NGN';
            }

            // Get exchange rate and convert to base currency (NGN)
            if ($donation->currency !== 'NGN') {
                $donation->exchange_rate = $currencyService->getExchangeRate($donation->currency, 'NGN');
                $donation->amount_in_base_currency = $currencyService->convertAmount(
                    $donation->amount, 
                    $donation->currency, 
                    'NGN'
                );
            } else {
                $donation->exchange_rate = 1.0;
                $donation->amount_in_base_currency = $donation->amount;
            }

            // Calculate processing fee based on currency
            if ($donation->cover_fee) {
                // Paystack charges 1.5% + fee (fee varies by currency)
                $fees = [
                    'NGN' => 100,
                    'USD' => 0.50,
                    'GBP' => 0.50,
                    'EUR' => 0.50,
                    'ZAR' => 2.00,
                    'KES' => 10.00,
                    'GHS' => 1.00,
                ];
                
                $fixedFee = $fees[$donation->currency] ?? 0;
                $donation->processing_fee = ($donation->amount * 0.015) + $fixedFee;
            } else {
                $donation->processing_fee = 0;
            }

            // Calculate total amount
            $donation->total_amount = $donation->amount + $donation->processing_fee;
        });

        static::created(function ($donation) {
            // Generate receipt number after creation
            if (empty($donation->receipt_number)) {
                $donation->receipt_number = 'RCP-' . date('Ymd') . '-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT);
                $donation->saveQuietly();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function report(): HasMany
    {
        return $this->hasMany(Reports::class);
    }

    // Accessor for formatted amount with currency
    public function getFormattedAmountAttribute(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatAmount($this->amount, $this->currency);
    }

    // Accessor for formatted total amount
    public function getFormattedTotalAmountAttribute(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatAmount($this->total_amount, $this->currency);
    }

    // Get currency symbol
    public function getCurrencySymbol(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getCurrencySymbol($this->currency);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'successful';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}