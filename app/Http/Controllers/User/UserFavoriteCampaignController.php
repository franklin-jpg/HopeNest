<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFavoriteCampaignController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favoriteCampaigns = $user->favoriteCampaigns()
            ->with(['category'])
            ->withPivot('notify_when_close', 'notified', 'created_at')
            ->latest('favorite_campaigns.created_at')
            ->paginate(12);

        return view('user.favorites.index', compact('favoriteCampaigns'));
    }
}