<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Mail\VerificationMail;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

 


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    // register field validation and sending of otp
    public function store(Request $request): RedirectResponse
    {
        // dd('hi');
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
               'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'g-recaptcha-response' => ['required'],

                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                // for custom error message
                'name.required' => 'Name field cannot be empty',
                'name.string' => 'Name must be in string type',
                'name.max:255' => 'Name must be maximum of 225 words or below',
                'email.required' => 'email field is required',
                'email.lowercase' => 'Email nust be in lowercase',
                'email.string' => 'Email must be in string type',
                'email.max:255' => 'Email must be maximum of 225 words or below',
                'email.unique:users,email' => 'Email must unique',
                'password.required' => 'Password field cannot be empty',
                'password.confrimed' => 'please confirm your password',
                 'g-recaptcha-response.required' => 'Please verify you are not a robot.',
            
            ]);


             // verify reCAPTCHA with Google
              $recaptcha = $request->input('g-recaptcha-response');
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptcha,
            'remoteip' => $request->ip(),
        ])->json();

        if (! ($recaptchaResponse['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])->withInput();
        }


            DB::beginTransaction();

            // generate otp and token
            $otp = rand(100000, 999999);
            $verificationToken = Str::random(40);
             $expiresAt = Carbon::now()->addMinutes(2);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';



            $user->email_verification_token = $verificationToken;
             $user->email_verification_otp = $otp;
              $user->otp_expires_at = $expiresAt;

            $user->save();

            // dd($user);
  

          
            Mail::to($user->email)->send(new VerificationMail($otp));
           

            DB::commit();

    // //             // Notify admins

    //           $admins = User::where('role', 'admin')->get();
    // foreach ($admins as $admin) {
    //     $admin->notify(new NewUserRegistered($user));
    // }
        return redirect()->route('verify.otp', [
    'token' => $verificationToken,
     ])->with([
    'success' => 'We have sent an OTP for your email verification',
    'otpExpiresAt' => optional($user->otp_expires_at)->toIso8601String(),
]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Registration Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


   public function showVerificationForm($token)
{
    $user = User::where('email_verification_token', $token)->first();
    if (!$user) {
        abort(404, 'Invalid verification token');
    }

    return view('auth.verify-email', [
        'token' => $token,
        'email' => $user->email,
       'otpExpiresAt' => optional($user->otp_expires_at)->toIso8601String(),

    ]);
}


    

// verifying of opt
    public function verifyOtp(Request $request, $token) 
    {
        try{
            $request->validate([
                'token' => 'required',
                'otp' => 'required|numeric|digits:6'
            ], [
                'otp.required' => 'please enter the verification code',
            ]);


            // first()-> this function take or request  the first user which has thesame verification token
            $user = User::where('email_verification_token', $token)->first();

            if(!$user){
                return redirect()->back()->with('error', 'Invalid verification token');
            }

            if ($user->email_verification_otp !== $request->otp) {
              return redirect()->back()->with('error', 'Invailed otp');
            }

                if (Carbon::now()->greaterThan($user->otp_expires_at)) {
        return back()->withErrors(['otp' => 'OTP has expired']);
    }


            // now() -> this method tells the current time and date a user is being verified
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->email_verification_otp = null;

            $user->save();
            //  dd('user logged in ');
               return redirect()->route('login')->with('success', 'Email verification completed, please login');

            Auth::login($user);
       
            // return redirect()->route('user.dashboard')->with('success', 'your email has verified and you have successfully logged in');
        } catch (Exception $e){
            Log::error('User Registration Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());

        }
        
    }


public function resendOtp(Request $request, $token)
{
    try {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found. Please restart the registration process.'], 404);
        }

     

        // Optionally, ensure the user has not already completed reset
        if ($user->otp_verified_at) {
            return response()->json(['error' => 'This link has already been used.']);
        }

        // Generate new OTP and expiry
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(2);

        // Update user record
        $user->password_reset_otp = $otp;
        $user->otp_expires_at = $expiresAt;
        $user->save();

        // Send OTP email again
        Mail::to($user->email)->send(new PasswordResetOtpMail($otp));

        return response()->json([
            'success' => 'A new OTP has been sent to your email.',
            'otpExpiresAt' => $expiresAt->toIso8601String()
        ]);
    } catch (Exception $e) {
        Log::error('Resend OTP Error: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
    }
}




}