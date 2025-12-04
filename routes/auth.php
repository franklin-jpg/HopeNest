<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('submit.user');

    // // Social Login
    // Route::get('/login/{provider}', [SocialController::class, 'redirect'])->name('social.login');
    // Route::get('/login/{provider}/callback', [SocialController::class, 'callback']);

    // Email OTP Verification
    Route::get('verify-otp/{token}', [RegisteredUserController::class, 'showVerificationForm'])->name('verify.otp');
    Route::post('/verify-otp/{token}', [RegisteredUserController::class, 'verifyOtp'])->name('user.verify-otp');
    Route::post('/resend-otp/{token}', [RegisteredUserController::class, 'resendOtp'])->name('user.resend-otp');

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    
 // Forgot / Reset Password
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forgot-password', 'ShowEmailForm')->name('password.reset');
    Route::post('/forgot-password', 'submitEmail')->name('email.submit');
    Route::get('confirm-code/{token}', 'showConfirmationCode')->name('confirm.code');
    Route::post('/verify-password-otp/{token}', 'verifyPasswordOtp')->name('user.verify-password-otp');
    Route::post('/resend-code/{token}', 'resendOtp')->name('resend.code');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('password_reset_form');
    Route::post('/reset-password/{token}', 'resetPassword')->name('reset.password.submit');
});

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

