<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'dark_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password_reset_token_expires_at' => 'datetime',
    ];



    public function isDarkMode(): bool
{
    return $this->dark_mode;
}

public function toggleDarkMode(): void
{
    $this->update(['dark_mode' => !$this->dark_mode]);
}

  public function profileImageUrl()
  {
    return $this->profile && $this->profile->profile_image ? asset('storage/' .
    $this->profile->profile_image) : asset('dashboard_assets/images/Avater.jpg');
  }

  public function contacts(): HasMany
  {
    return $this->hasMany(Contact::class);
  }
public function profile():HasOne
{
    return $this->hasOne(Profile::class);
}

public function donation(): HasMany
{
    return $this->hasMany(Donation::class);
}

public function views(): HasMany
{
    return $this->hasMany(CampaignView::class);
}

public function subscriber(): HasOne
{
    return $this->hasOne(Subscriber::class);

}

public function volunteer()
   {
       return $this->hasOne(Volunteer::class);
   }

   public function isVolunteer()
{
    return $this->volunteer()->exists();
}

public function isApprovedVolunteer()
{
    return $this->volunteer()->where('status', 'approved')->exists();
}



// Add these methods to your existing User model

/**
 * Get user's favorite campaigns
 */
public function favoriteCampaigns()
{
    return $this->belongsToMany(Campaign::class, 'favorite_campaigns')
        ->withPivot('notify_when_close', 'notified')
        ->withTimestamps();
}

/**
 * Get favorite campaigns with pivot data
 */
public function favorites()
{
    return $this->hasMany(FavoriteCampaign::class);
}

/**
 * Check if user has favorited a campaign
 */
public function hasFavorited($campaignId): bool
{
    return $this->favoriteCampaigns()->where('campaign_id', $campaignId)->exists();
}

/**
 * Add campaign to favorites
 */
public function addToFavorites($campaignId, $notifyWhenClose = false)
{
    return $this->favoriteCampaigns()->attach($campaignId, [
        'notify_when_close' => $notifyWhenClose,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * Remove campaign from favorites
 */
public function removeFromFavorites($campaignId)
{
    return $this->favoriteCampaigns()->detach($campaignId);
}

/**
 * Toggle favorite status
 */
public function toggleFavorite($campaignId, $notifyWhenClose = false)
{
    if ($this->hasFavorited($campaignId)) {
        $this->removeFromFavorites($campaignId);
        return false;
    } else {
        $this->addToFavorites($campaignId, $notifyWhenClose);
        return true;
    }
}


}