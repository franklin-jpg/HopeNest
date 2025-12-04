<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected array $supportedCurrencies = [
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦', 'flag' => '🇳🇬'],
        'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'flag' => '🇺🇸'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€', 'flag' => '🇪🇺'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'flag' => '🇬🇧'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$', 'flag' => '🇨🇦'],
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$', 'flag' => '🇦🇺'],
        'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R', 'flag' => '🇿🇦'],
        'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'flag' => '🇰🇪'],
        'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => 'GH₵', 'flag' => '🇬🇭'],
    ];

    public function getSupportedCurrencies(): array
    {
        return $this->supportedCurrencies;
    }

    public function getExchangeRate(string $fromCurrency, string $toCurrency = 'NGN'): float
    {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        // Cache exchange rates for 1 hour
        $cacheKey = "exchange_rate_{$fromCurrency}_{$toCurrency}";
        
        return Cache::remember($cacheKey, 3600, function () use ($fromCurrency, $toCurrency) {
            return $this->fetchExchangeRate($fromCurrency, $toCurrency);
        });
    }

    protected function fetchExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        try {
            // Using exchangerate-api.com (free tier available)
            $apiKey = config('services.exchange_rate.api_key'); // Add to .env
            
            if ($apiKey) {
                $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/pair/{$fromCurrency}/{$toCurrency}");
                
                if ($response->successful()) {
                    return $response->json()['conversion_rate'];
                }
            }

            // Fallback to static rates if API fails
            return $this->getFallbackRate($fromCurrency, $toCurrency);
            
        } catch (\Exception $e) {
            Log::error("Exchange rate fetch failed: " . $e->getMessage());
            return $this->getFallbackRate($fromCurrency, $toCurrency);
        }
    }

    protected function getFallbackRate(string $fromCurrency, string $toCurrency): float
    {
        // Approximate rates as fallback (update these periodically)
        $rates = [
            'USD_NGN' => 1550.00,
            'EUR_NGN' => 1680.00,
            'GBP_NGN' => 1950.00,
            'CAD_NGN' => 1140.00,
            'AUD_NGN' => 1010.00,
            'ZAR_NGN' => 85.00,
            'KES_NGN' => 12.00,
            'GHS_NGN' => 130.00,
        ];

        $key = "{$fromCurrency}_{$toCurrency}";
        $reverseKey = "{$toCurrency}_{$fromCurrency}";

        if (isset($rates[$key])) {
            return $rates[$key];
        } elseif (isset($rates[$reverseKey])) {
            return 1 / $rates[$reverseKey];
        }

        return 1.0;
    }

    public function convertAmount(float $amount, string $fromCurrency, string $toCurrency = 'NGN'): float
    {
        $rate = $this->getExchangeRate($fromCurrency, $toCurrency);
        return round($amount * $rate, 2);
    }

    public function getCurrencySymbol(string $currency): string
    {
        return $this->supportedCurrencies[$currency]['symbol'] ?? $currency;
    }

    public function formatAmount(float $amount, string $currency): string
    {
        $symbol = $this->getCurrencySymbol($currency);
        return $symbol . number_format($amount, 2);
    }
}