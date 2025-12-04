<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeNewsletter;
use App\Models\Subscriber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:subscribers,email',
                'name'  => 'nullable|string|max:255',
            ]);

            // 1. Save to local database
            $subscriber = Subscriber::create([
                'email'       => $request->email,
                'name'        => $request->name,
                'ip_address'  => $request->ip(),
                'subscribed_at' => now(),
            ]);

            // 2. Sync with Mailchimp
            $this->syncToMailchimp($request->email, $request->name ?? '');

            // 3. Send welcome email
            Mail::to($subscriber->email)->queue(new WelcomeNewsletter($subscriber));

            return redirect()
                ->route('newsletter.success')
                ->with('subscribed_email', $request->email);

        } catch (Exception $e) {
            // ✅ REMOVED dd() - PRODUCTION READY
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('newsletter_error', 'Oops! Something went wrong. Please try again.');
        }
    }

    /**
     * Sync subscriber to Mailchimp using pure API
     */
    private function syncToMailchimp(string $email, string $name = ''): bool
    {
        $apiKey = config('services.mailchimp.key');
        $listId = config('services.mailchimp.list_id');
        $dc = substr($apiKey, strpos($apiKey, '-') + 1);

        if (!$apiKey || !$listId) {
            Log::warning('Mailchimp config missing - skipping sync');
            return false;
        }

        $response = Http::withBasicAuth('user', $apiKey)
            ->post("https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members", [
                'email_address' => $email,
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $name,
                ]
            ]);

        if (!$response->successful()) {
            Log::warning('Mailchimp sync failed: ' . $response->body());
        }

        return $response->successful();
    }

    /**
     * Show unsubscribe form ✅ SINGLE METHOD - FIXED!
     */
    public function showUnsubscribeForm(Request $request)
    {
        $email = $request->query('email');

        // ✅ Allows BOTH: direct visit OR email link
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return view('newsletter.unsubscribe', ['email' => null]);
        }

        return view('newsletter.unsubscribe', compact('email'));
    }

    /**
     * Process unsubscribe request ✅ REMOVED dd()
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:subscribers,email'
        ]);

        $email = $request->email;

        try {
            $this->unsubscribeFromMailchimp($email);

            Subscriber::where('email', $email)
                ->update(['unsubscribed_at' => now()]);

            return redirect()
                ->route('newsletter.unsubscribed')
                ->with('status', 'You have been successfully unsubscribed.');

        } catch (Exception $e) {
            Log::warning("Failed to unsubscribe {$email}: " . $e->getMessage());

            return redirect()
                ->route('newsletter.unsubscribed')
                ->with('status', 'You are already unsubscribed or the email was not found.');
        }
    }

    /**
     * Unsubscribe from Mailchimp using pure API
     */
    private function unsubscribeFromMailchimp(string $email): bool
    {
        $apiKey = config('services.mailchimp.key');
        $listId = config('services.mailchimp.list_id');
        $dc = substr($apiKey, strpos($apiKey, '-') + 1);

        if (!$apiKey || !$listId) {
            Log::warning('Mailchimp config missing - skipping unsubscribe');
            return false;
        }

        $subscriberHash = md5(strtolower($email));
        
        $response = Http::withBasicAuth('user', $apiKey)
            ->delete("https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members/{$subscriberHash}");

        if (!$response->successful()) {
            Http::withBasicAuth('user', $apiKey)
                ->patch("https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members/{$subscriberHash}", [
                    'status' => 'unsubscribed'
                ]);
        }

        return true;
    }
}