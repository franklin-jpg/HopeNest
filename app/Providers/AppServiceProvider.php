<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Campaign;
use App\Models\Volunteer;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   // app/Providers/AppServiceProvider.php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->singleton(CurrencyService::class, function ($app) {
        return new CurrencyService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    View::composer('*', function ($view) {
        if (request()->route('slug')) {
            $campaign = Campaign::where('slug', request()->route('slug'))->first();
            if ($campaign) {
                $view->with('campaign', $campaign);
            }
        }
    });
    

        View::composer('partials.volunteer.sidebar', function ($view) {
        $volunteer = Volunteer::where('user_id', Auth::id())->first();
        $stats = null;
        if ($volunteer) {
            $stats = [
                'total_hours' => $volunteer->hours()->where('volunteer_hours.status', 'approved')->sum('hours'),
                // add other stats here if needed...
            ];
        }
        $view->with('stats', $stats);
    });
}

}



