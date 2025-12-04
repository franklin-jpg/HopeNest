<?php

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserDonationController;
use App\Http\Controllers\User\UserFavoriteCampaignController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')
    ->name('user.')
    ->middleware('auth', 'can:access-user-dashboard')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // My Donations
        Route::get('/donations', [UserDonationController::class, 'index'])
            ->name('donations.index');
            
        Route::get('/donations/{donation}', [UserDonationController::class, 'show'])
            ->name('donations.show');
            
        Route::get('/donations/{donation}/receipt', [UserDonationController::class, 'receipt'])
            ->name('donations.receipt');
            
        Route::get('/donations/{donation}/pdf', [UserDonationController::class, 'downloadPdf'])
            ->name('donations.pdf');

        // Profile Settings
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [UserProfileController::class, 'index'])
                ->name('index');
                
            Route::put('/update', [UserProfileController::class, 'update'])
                ->name('update');
                
            Route::put('/picture', [UserProfileController::class, 'updatePicture'])
                ->name('picture');
                
            Route::delete('/picture', [UserProfileController::class, 'removePicture'])
                ->name('picture.remove');
                
            Route::put('/password', [UserProfileController::class, 'updatePassword'])
                ->name('password');
                
            Route::put('/preferences', [UserProfileController::class, 'updatePreferences'])
                ->name('preferences');
                
            Route::delete('/delete', [UserProfileController::class, 'destroy'])
                ->name('delete');
        });

        // Favorite Campaigns
Route::post('/campaigns/{campaign}/favorite', [UserDashboardController::class, 'toggleFavorite'])
    ->name('campaigns.favorite');
    
Route::post('/campaigns/{campaign}/notification', [UserDashboardController::class, 'updateNotificationPreference'])
    ->name('campaigns.notification');

Route::get('/favorites', [UserFavoriteCampaignController::class, 'index'])
    ->name('favorites.index');
    });