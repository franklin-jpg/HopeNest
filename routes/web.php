<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\Paystack\DonationController;
use App\Http\Controllers\Auth\Paystack\FormDonationController;
use App\Http\Controllers\Auth\WelcomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ImpactStoryPublicController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerApplicationController;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/campaigns', [WelcomeController::class, 'all_campaigns'])->name('all.campaigns');
Route::get('/campaigns/{campaign:slug}', [WelcomeController::class, 'show'])->name('show.single');

// Donation routes - FIXED
Route::middleware(['auth'])->group(function () {
    // Show donation form - use slug-based route model binding
    Route::get('/donate/{campaign:slug}', [FormDonationController::class, 'showForm'])->name('form.donation');
    
    Route::post('/donations/process', [DonationController::class, 'process'])->name('donations.process');
    Route::get('/donations/callback', [DonationController::class, 'callback'])->name('donations.callback');
    Route::get('/donations/success/{id}', [DonationController::class, 'success'])->name('donations.success');
    Route::get('/donations/failed/{reference}', [DonationController::class, 'failed'])->name('donations.failed');
    Route::get('/donations/{id}/receipt', [DonationController::class, 'downloadReceipt'])->name('donations.receipt');
});
// Newsletter subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

// Unsubscribe flow (SAFE - requires confirmation)
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'showUnsubscribeForm'])
    ->name('newsletter.unsubscribe.form');
    
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

// Success pages
Route::get('/newsletter/success', function () {
    return view('newsletter.subscribe-success');
})->name('newsletter.success');

Route::get('/newsletter/unsubscribed', function () {
    return view('newsletter.unsubscribed');
})->name('newsletter.unsubscribed');


// blog post route 
Route::get('/blog', function () {
    $posts = BlogPost::with(['categories', 'tags'])
        ->published()
        ->latest('published_at')
        ->paginate(12);
    return view('blog.index', compact('posts'));
})->name('blog.index');

Route::get('/blog/{post:slug}', function (BlogPost $post) {
    abort_if(!$post->isPublished(), 404);
    $post->increment('views_count');
    $related = BlogPost::published()
        ->where('id', '!=', $post->id)
        ->inRandomOrder()->limit(3)->get();
    return view('blog.show', compact('post', 'related'));
})->name('blog.show');




Route::middleware('auth')->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/apply', [VolunteerApplicationController::class, 'create'])->name('apply');
    Route::post('/apply', [VolunteerApplicationController::class, 'store'])->name('store');
    Route::get('/status', [VolunteerApplicationController::class, 'status'])->name('status');
    Route::get('/dashboard', [VolunteerApplicationController::class, 'dashboard'])->name('dashboard');
    Route::post('/log-hours', [VolunteerApplicationController::class, 'logHours'])->name('log-hours');
});


// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// impact stories routeas
Route::prefix('impact-stories')->name('impact-stories.')->group(function () {
    Route::get('/', [ImpactStoryPublicController::class, 'index'])->name('index');
    Route::get('/{slug}', [ImpactStoryPublicController::class, 'show'])->name('show');
});

// about route
Route::get('/about-us', [AboutUsController::class, 'aboutUs'])->name('about-us');
Route::get('/faq', [AboutUsController::class, 'faq'])->name('faq');
// contact route
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
// In your admin route group
Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])
    ->name('admin.contacts.reply');

// Route::get('/dashboard', function () {
//     return view('Admin.dashboard');
// })->middleware(['auth'])->name('admin.dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/volunteer.php';