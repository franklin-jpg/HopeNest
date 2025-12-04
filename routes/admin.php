<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\CampaignAnalyticsController;
use App\Http\Controllers\Admin\CampaignCategoryController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignReportController;
use App\Http\Controllers\Admin\CampaignUpdateController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\DonationReportController;
use App\Http\Controllers\Admin\ImpactStoryController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VolunteerController;
use App\Http\Controllers\Admin\VolunteerHourController;
use App\Http\Controllers\Admin\VolunteerReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth', 'can:access-admin-dashboard')
    ->group(function () {
        
        // Dashboard route
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin profile routes
        Route::prefix('profile')->name('profile.')->controller(AdminProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'updateProfile')->name('update');
            Route::patch('/password', 'updatePassword')->name('password');
        });

        // Campaign Categories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/index', [CampaignCategoryController::class, 'index'])->name('index');
            Route::get('/archived', [CampaignCategoryController::class, 'archived'])->name('archived');
            Route::get('/create', [CampaignCategoryController::class, 'create'])->name('create');
            Route::post('/', [CampaignCategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CampaignCategoryController::class, 'edit'])->name('edit');
            Route::patch('/{category}', [CampaignCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CampaignCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{category}/archive', [CampaignCategoryController::class, 'archive'])->name('archive');
            Route::patch('/{id}/restore', [CampaignCategoryController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [CampaignCategoryController::class, 'forceDelete'])
                 ->name('forceDelete')
                 ->withTrashed();
        });

        // Campaign Routes
        Route::prefix('campaigns')->name('campaigns.')->group(function () {
            // List & Archive
            Route::get('/', [CampaignController::class, 'index'])->name('index');
            Route::get('/archived', [CampaignController::class, 'archived'])->name('archived');
            
            // CRUD Operations
            Route::get('/create', [CampaignController::class, 'create'])->name('create');
            Route::post('/', [CampaignController::class, 'store'])->name('store');
            Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
            Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
            Route::patch('/{campaign}', [CampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
            
            // Campaign Actions
            Route::patch('/{campaign}/toggle-status', [CampaignController::class, 'toggleStatus'])->name('toggle-status');
            Route::patch('/{campaign}/mark-completed', [CampaignController::class, 'markCompleted'])->name('mark-completed');
            Route::post('/{campaign}/duplicate', [CampaignController::class, 'duplicate'])->name('duplicate');
            
            // Archive Operations
            Route::patch('/{id}/restore', [CampaignController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [CampaignController::class, 'forceDelete'])->name('forceDelete');
        });

        // Campaign Updates Routes
        Route::prefix('campaigns/{campaign}/updates')->name('campaigns.updates.')->group(function () {
            Route::get('/', [CampaignUpdateController::class, 'index'])->name('index');
            Route::get('/create', [CampaignUpdateController::class, 'create'])->name('create');
            Route::post('/', [CampaignUpdateController::class, 'store'])->name('store');
            Route::get('/{update}/edit', [CampaignUpdateController::class, 'edit'])->name('edit');
            Route::patch('/{update}', [CampaignUpdateController::class, 'update'])->name('update');
            Route::delete('/{update}', [CampaignUpdateController::class, 'destroy'])->name('destroy');
            Route::post('/{update}/publish', [CampaignUpdateController::class, 'publish'])->name('publish');
            Route::post('/{update}/resend-emails', [CampaignUpdateController::class, 'resend-emails'])->name('resend-emails');
        });

        // Campaign Analytics Routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [CampaignAnalyticsController::class, 'index'])->name('index');
            Route::get('/campaign/{campaign}', [CampaignAnalyticsController::class, 'show'])->name('show');
            Route::get('/campaign/{campaign}/export', [CampaignAnalyticsController::class, 'export'])->name('export');
            Route::get('/compare', [CampaignAnalyticsController::class, 'compare'])->name('compare');
        });
        
        // Donation Routes
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [DonationController::class, 'index'])->name('index');
            Route::get('/failed', [DonationController::class, 'failed'])->name('failed');
            Route::post('/{donation}/send-retry-link', [DonationController::class, 'sendRetryLink'])->name('send-retry-link');
            Route::get('/campaign/{campaign}', [DonationController::class, 'campaign'])->name('campaign');
            Route::get('/{donation}', [DonationController::class, 'show'])->name('show');
            Route::get('/{donation}/details', [DonationController::class, 'details'])->name('details');
            Route::patch('/{donation}/status', [DonationController::class, 'updateStatus'])->name('update-status');
            Route::post('/{donation}/resend-receipt', [DonationController::class, 'resendReceipt'])->name('resend-receipt');
            Route::patch('/{donation}/mark-tax-cert', [DonationController::class, 'markTaxCertificateSent'])->name('mark-tax-cert');
            Route::post('/{donation}/refund', [DonationController::class, 'refund'])->name('refund');
            Route::post('/{donation}/thank-you', [DonationController::class, 'sendThankYou'])->name('send-thank-you');
        });

        // Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            // Donation Reports
            Route::get('/donations', [DonationReportController::class, 'index'])->name('donations.index');
            Route::get('/donations/export', [DonationReportController::class, 'export'])->name('donations.export');
            
            // Campaign Reports
            Route::get('/campaigns', [CampaignReportController::class, 'index'])->name('campaigns.index');
            Route::get('/campaigns/export', [CampaignReportController::class, 'export'])->name('campaigns.export');
        });

        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/donors', [AdminUserController::class, 'donors'])->name('donors');
            Route::get('/subscribers', [AdminUserController::class, 'subscribers'])->name('subscribers');
            Route::get('/export', [AdminUserController::class, 'export'])->name('export');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::patch('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
            Route::patch('/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('update-role');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

        // Volunteer Management Routes
        Route::prefix('volunteers')->name('volunteers.')->group(function () {
            Route::get('/', [VolunteerController::class, 'index'])->name('index');
            Route::get('/pending', [VolunteerController::class, 'pending'])->name('pending');
            Route::get('/{volunteer}', [VolunteerController::class, 'show'])->name('show');
            
            // Approval Actions
            Route::patch('/{volunteer}/approve', [VolunteerController::class, 'approve'])->name('approve');
            Route::patch('/{volunteer}/reject', [VolunteerController::class, 'reject'])->name('reject');
            Route::patch('/{volunteer}/suspend', [VolunteerController::class, 'suspend'])->name('suspend');
            Route::patch('/{volunteer}/reactivate', [VolunteerController::class, 'reactivate'])->name('reactivate');
            
            // Campaign Assignment
            Route::post('/{volunteer}/assign-campaign', [VolunteerController::class, 'assignToCampaign'])->name('assign-campaign');
            Route::delete('/{volunteer}/campaigns/{campaign}', [VolunteerController::class, 'removeFromCampaign'])->name('remove-campaign');
            
            // Communication
            Route::post('/bulk-email', [VolunteerController::class, 'bulkEmail'])->name('bulk-email');
            
            // Notes & Export
            Route::patch('/{volunteer}/notes', [VolunteerController::class, 'updateNotes'])->name('update-notes');
            Route::get('/export/list', [VolunteerController::class, 'export'])->name('export');
            Route::delete('/{volunteer}', [VolunteerController::class, 'destroy'])->name('destroy');
        });

        // Volunteer Hours Management Routes
        Route::prefix('volunteer-hours')->name('volunteer-hours.')->group(function () {
            Route::get('/', [VolunteerHourController::class, 'index'])->name('index');
            Route::get('/pending', [VolunteerHourController::class, 'pending'])->name('pending');
            Route::patch('/{hour}/approve', [VolunteerHourController::class, 'approve'])->name('approve');
            Route::patch('/{hour}/reject', [VolunteerHourController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [VolunteerHourController::class, 'bulkApprove'])->name('bulk-approve');
        });

        Route::prefix('volunteer-reports')->name('volunteers-reports.')->group(function () {
    Route::get('/', [VolunteerReportController::class, 'index'])->name('index');
    Route::get('/hours', [VolunteerReportController::class, 'hoursReport'])->name('hours');
    Route::get('/export', [VolunteerReportController::class, 'export'])->name('export');
});

 // Blog Posts Routes
Route::prefix('blogs')->name('blogs.')->group(function () {
   
    Route::resource('blog', BlogController::class);
    Route::post('blog/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blog.toggle-status');
    
    //categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
        Route::get('/create', [BlogCategoryController::class, 'create'])->name('create');
        Route::post('/', [BlogCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [BlogCategoryController::class, 'edit'])->name('edit');
        Route::patch('/{category}', [BlogCategoryController::class, 'update'])->name('update'); // ✅ ONLY ONE!
        Route::delete('/{category}', [BlogCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Tags
    Route::prefix('tags')->name('tags.')->group(function () {
       Route::get('/', [BlogTagController::class, 'index'])->name('index');
       Route::get('/create', [BlogTagController::class, 'create'])->name('create');

        Route::post('/', [BlogTagController::class, 'store'])->name('store');
        Route::get('/{tag}/edit', [BlogTagController::class, 'edit'])->name('edit');
        Route::patch('/{tag}', [BlogTagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [BlogTagController::class, 'destroy'])->name('destroy');
    });
});

// Impact Stories Routes
Route::prefix('impact-stories')->name('impact-stories.')->group(function () {
    Route::get('/', [ImpactStoryController::class, 'index'])->name('index');
    Route::get('/create', [ImpactStoryController::class, 'create'])->name('create');
    Route::post('/', [ImpactStoryController::class, 'store'])->name('store');
    Route::get('/{impactStory}', [ImpactStoryController::class, 'show'])->name('show');
    Route::get('/{impactStory}/edit', [ImpactStoryController::class, 'edit'])->name('edit');
    Route::patch('/{impactStory}', [ImpactStoryController::class, 'update'])->name('update');
    Route::delete('/{impactStory}', [ImpactStoryController::class, 'destroy'])->name('destroy');
    
    Route::post('/{impactStory}/toggle-status', [ImpactStoryController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{impactStory}/toggle-featured', [ImpactStoryController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::get('/{impactStory}/analytics', [ImpactStoryController::class, 'analytics'])->name('analytics');
    
    Route::patch('/{id}/restore', [ImpactStoryController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [ImpactStoryController::class, 'forceDelete'])->name('forceDelete');
});

//  setting Routes

Route::get('/settings', [SettingController::class, 'edit'])
            ->name('settings');

        Route::post('/settings', [SettingController::class, 'update'])
            ->name('settings.update');


            // contact route


             Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{id}/replied', [ContactController::class, 'markReplied'])->name('contacts.replied');
    Route::patch('/contacts/{id}/notes', [ContactController::class, 'updateNotes'])->name('contacts.notes');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');



// Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('destroy-all');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
});




    });