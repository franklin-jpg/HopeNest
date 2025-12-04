<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function ShowEmailForm()
    {
        return view('auth.passwords.ForgotPassword');
    }

    public function submitEmail(Request $request)
    {
        try {
            $request->validate(
                [
                    'email' => ['required', 'email']
                ],
                [
                    'email.required' => 'Email field cannot be empty',
                ]
            );

            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()->withErrors(['email' => 'We could not find a user with this email address']);
            }


            // Single source of truth for expiry
            $expiresAt = Carbon::now()->addMinutes(2);

            $resetToken = Str::random(40);
            $otp = rand(100000, 999999);

            $user->password_reset_token = $resetToken;
            $user->password_reset_otp = $otp;
            $user->password_reset_token_expires_at = $expiresAt;
            $user->otp_expires_at = $expiresAt;
            $user->save();

            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));

            DB::commit();

            return redirect()->route('confirm.code', ['token' => $resetToken])
                ->with('success', 'We have sent an OTP to your email to reset your password.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Forgot password error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? null,
            ]);

            return redirect()->back()->with('error', 'Something went wrong, please try again later');
        }
    }

    public function showConfirmationCode(Request $request, $token)
    {
        $user = User::where('password_reset_token', $token)
            ->whereNotNull('password_reset_token_expires_at')
            ->where('password_reset_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect()->route('password.reset')
                ->with('error', 'Invalid or expired token. Please request a new one.');
        }

        if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return redirect()->route('password.reset')
                ->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        $maskedEmail = $this->maskedEmail($user->email);

        return view('auth.passwords.confirm-code', [
            'token' => $token,
            'email' => $maskedEmail,
            // pass server iso strings (or null)
            'ExpiresAt' => $user->password_reset_token_expires_at ? $user->password_reset_token_expires_at->toIso8601String() : null,
            'otpExpiresAt' => $user->otp_expires_at ? $user->otp_expires_at->toIso8601String() : null,
        ]);
    }
    public function verifyPasswordOtp(Request $request, $token)
    {
        try {
            // Only validate OTP, token comes from route param $token
            $request->validate([
                'otp' => 'required|numeric|digits:6'
            ], [
                'otp.required' => 'Please enter the verification code',
                'otp.digits' => 'Verification code must be 6 digits',
            ]);

            $user = User::where('password_reset_token', $token)
                ->whereNotNull('password_reset_token_expires_at')
                ->where('password_reset_token_expires_at', '>', Carbon::now())
                ->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Invalid verification token');
            }

            // Check if OTP has expired
            if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
                return redirect()->back()->with('error', 'OTP has expired. Please request a new one.');
            }

            if ((string)$user->password_reset_otp !== (string)$request->otp) {
                return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
            }

            return redirect()->route('password_reset_form', ['token' => $token])
                ->with('success', 'Your OTP has been verified');
        } catch (Exception $e) {
            Log::error('Verify OTP Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    // Resend OTP - Returns JSON for AJAX
    public function resendOtp(Request $request, $token)
    {
        try {
            // Find user by token only - don't check expiry for resend
            $user = User::where('password_reset_token', $token)->first();

            if (!$user) {
                return response()->json(['error' => 'Invalid token.'], 400);
            }

            // Check if the original token request was too long ago (e.g., 15 minutes)
            // This prevents abuse while still allowing resends
            if (
                $user->password_reset_token_expires_at &&
                Carbon::now()->diffInMinutes($user->password_reset_token_expires_at) > 15
            ) {
                return response()->json(['error' => 'Token has expired. Please request a new password
                 reset.'], 400);
            }
            
    //    if ($user->otp_expires_at && Carbon::now()->greaterThan($user->otp_expires_at)) {
    //     return response()->json(['error' => 'OTP has expired. Or Invalid OTP'], 400);
    //     }

            $expiresAt = Carbon::now()->addMinutes(2);
            $otp = rand(100000, 999999);

            $user->update([
                'password_reset_otp' => $otp,
                'password_reset_token_expires_at' => $expiresAt,
                'otp_expires_at' => $expiresAt,
            ]);

            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));

            return response()->json([
                'success' => 'A new OTP has been sent to your email.',
                'otpExpiresAt' => $expiresAt->toIso8601String(),
            ]);
        } catch (Exception $e) {
            Log::error('Resend OTP Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong. Try again later.'], 500);
        }
    }


    public function showResetPasswordForm($token)
    {
        $user = User::where('password_reset_token', $token)
            ->whereNotNull('password_reset_token_expires_at')
            ->where('password_reset_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect()->route('password.reset')
                ->with('error', 'Invalid or expired token');
        }

        return view('auth.passwords.reset-password', [
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ], [
                'password.required' => 'Please enter a new password.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Passwords do not match.',
            ]);

            $user = User::where('password_reset_token', $token)
                ->whereNotNull('password_reset_token_expires_at')
                ->where('password_reset_token_expires_at', '>', Carbon::now())
                ->first();

            if (!$user) {
                return redirect()->route('password.reset')
                    ->with('error', 'Invalid or expired token.');
            }

            $user->password = bcrypt($request->password);
            $user->password_reset_token = null;
            $user->password_reset_otp = null;
            $user->password_reset_token_expires_at = null;
            $user->otp_expires_at = null;
            $user->save();

            return redirect()->route('login')
                ->with('success', 'Your password has been reset successfully.');
        } catch (Exception $e) {
            Log::error('Reset Password Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }



    // Email masking
    private function maskedEmail($email): string
    {
        $emailParts = explode("@", $email);
        $name = $emailParts[0];
        $domain = $emailParts[1] ?? '';

        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));

        return $maskedName . '@' . $domain;
    }
}
