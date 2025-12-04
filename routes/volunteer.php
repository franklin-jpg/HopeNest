<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Volunteer\AssignedCampaignsController;
use App\Http\Controllers\Volunteer\DonationController;               // Fixed namespace
use App\Http\Controllers\Volunteer\VolunteerDashboardController;
use App\Http\Controllers\Volunteer\VolunteerHourController;
use App\Http\Controllers\Volunteer\VolunteerProfileController;
use App\Http\Controllers\VolunteerApplicationController;
use Illuminate\Support\Facades\Route;

// Volunteer Area – All routes under /volunteer prefix
Route::prefix('volunteer')
    ->name('volunteer.')
    ->middleware(['auth', 'can:access-volunteer-dashboard'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [VolunteerDashboardController::class, 'index'])
            ->name('dashboard');

        // Assigned Campaigns
        Route::get('/assigned-campaigns', [AssignedCampaignsController::class, 'index'])
            ->name('assigned-campaigns.index');

        Route::prefix('volunteer-donations')->name('volunteer-donations.')->group(function () {
            Route::get('/', [DonationController::class, 'index'])
                ->name('index');

            Route::get('/export/csv', [DonationController::class, 'export'])
                ->name('export');

            Route::get('/{donation}', [DonationController::class, 'show'])
                ->name('show');
        });

          // Volunteer Profile Routes
    Route::get('/profile', [VolunteerProfileController::class, 'index'])
        ->name('profile.index');

    Route::patch('/profile', [VolunteerProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::patch('/profile/password', [VolunteerProfileController::class, 'updatePassword'])
        ->name('profile.password');




            // Volunteer Hours - NEW ROUTES
        Route::prefix('hours')->name('hours.')->group(function () {
            Route::get('/', [VolunteerHourController::class, 'index'])
                ->name('index');
            Route::get('/create', [VolunteerHourController::class, 'create'])
                ->name('create');
            Route::post('/', [VolunteerHourController::class, 'store'])
                ->name('store');
            Route::get('/{hour}', [VolunteerHourController::class, 'show'])
                ->name('show');
            Route::get('/{hour}/edit', [VolunteerHourController::class, 'edit'])
                ->name('edit');
            Route::put('/{hour}', [VolunteerHourController::class, 'update'])
                ->name('update');
            Route::delete('/{hour}', [VolunteerHourController::class, 'destroy'])
                ->name('destroy');
            Route::get('/export/csv', [VolunteerHourController::class, 'export'])
                ->name('export');
        });

    });



      

// Global Auth Routes (outside volunteer area)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});