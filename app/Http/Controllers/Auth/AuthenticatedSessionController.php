<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Str;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {

        $request->session()->regenerate();
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
     * @param \Illuminate\Http\Request $request
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('Login attempt started');

        // Step 1: Authenticate
        $request->authenticate();
        Log::info('Authentication successful');

        // Step 2: Get user
        $user = User::find(Auth::id());
        Log::info('User retrieved', ['user_id' => $user?->id]);

        // Step 3: Check email verification
        if (!$user->email_verified_at) {
            Log::info('User email not verified');

            Auth::logout();
            Log::info('User logged out due to unverified email');

            try {
                DB::beginTransaction();
                Log::info('Started DB transaction for verification');

                $otp = rand(100000, 999999);
                $verificationToken = Str::random(40);

                $user->email_verification_token = $verificationToken;
                $user->email_verification_otp = $otp;
                $user->save();
                Log::info('User verification data saved', [
                    'otp' => $otp,
                    'token' => $verificationToken
                ]);

                Mail::to($user->email)->send(new VerificationMail($otp));
                Log::info('Verification mail sent to user');

                DB::commit();
                Log::info('DB transaction committed');

                return redirect()->route('verify.otp', ['token' => $verificationToken])
                    ->with('info', 'Please verify your email. A verification code has been sent.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Verification process failed', ['error' => $e->getMessage()]);
                return redirect()->route('login')->with('error', 'Verification process failed. Please try again.');
            }
        }

        Log::info('User is verified. Proceeding to login');

        $request->session()->regenerate(); // secure session
        Log::info('Session regenerated');

        // Final redirect to intended route
        Log::info('Redirecting user to intended page or dashboard');
        return $this->sendLoginResponse($request);
    }

    protected function sendLoginResponse(): RedirectResponse
    {
        $redirectRoutes = [
            'access-admin-dashboard' => 'admin.dashboard',
            'access-user-dashboard' => 'user.dashboard',
            'access-volunteer-dashboard' => 'volunteer.dashboard',


        ];
        foreach ($redirectRoutes as $gate => $route) {
            if (Gate::allows($gate)) {
                return redirect()->intended(route($route, absolute: false));
            }
        }
        Auth::logout();
        return redirect()->route('login')->with('error', 'your account does not have access to any
         dashboard');
    }

    /**
     *
     * 
     *  Destroy an authenticated session.
     * 
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
