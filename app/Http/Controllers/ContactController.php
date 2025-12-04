<?php

namespace App\Http\Controllers;

use App\Mail\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Exception;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('home.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:general,campaign,donation,technical,partnership,feedback',
            'message' => 'required|string|min:10',
        ]);

        try {
            $contact = Contact::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'unread',
            ]);

            $this->sendUserConfirmation($validated);
                // Notify admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new NewContactMessage($contact));
    }


            return back()->with('success', 'Thank you for contacting us! We will get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact Form Submission Failed: ' . $e->getMessage(), [
                'data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Oops! Something went wrong. Please try again later.');
        }
    }

    private function sendAdminNotification(array $data): void
    {
        try {
            Mail::send('emails.contact-admin', ['data' => $data], function ($message) use ($data) {
                $message->to(config('mail.admin_email', 'admin@hopenest.ng'))
                        ->subject('New Contact Form: ' . ucfirst($data['subject']));
            });
        } catch (Exception $e) {
            Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }
    }

    private function sendUserConfirmation(array $data): void
    {
        try {
            Mail::send('emails.contact-user', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])
                        ->subject('We\'ve Received Your Message – HopeNest');
            });
        } catch (Exception $e) {
            Log::error('Failed to send user confirmation email: ' . $e->getMessage());
        }
    }

public function reply(Request $request, Contact $contact)
{
    $request->validate([
        'reply_message' => 'required|string|min:10',
    ]);

    // Send email to user
    Mail::to($contact->email)->send(
        new ContactReply($contact, $request->reply_message, auth()->user()->name ?? 'HopeNest Team')
    );

    // Mark as replied automatically
    $contact->markAsReplied();

    return back()->with('success', 'Reply sent successfully to ' . $contact->email . '!');
}
}