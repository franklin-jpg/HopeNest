<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount('donation'); // ✅ Fixed: no array


        $query = User::query()
        ->withCount(['donation' => function ($q) {
            $q->where('status', 'successful'); 
        }]);
      
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);

        // Statistics - ✅ FIXED
        $stats = [
            'total_users' => User::count(),
            'total_donors' => User::whereHas('donation', function ($q) {
                $q->where('status', 'successful');
            })->count(),
            'new_donors_this_month' => User::whereHas('donation', function ($q) {
                $q->where('status', 'successful')
                  ->whereMonth('created_at', now()->month);
            })->count(),
            'active_users' => User::where('active', true)->count(),
            'inactive_users' => User::where('active', false)->count(),
            'newsletter_subscribers' => Subscriber::whereNull('unsubscribed_at')->count(),
            'total_revenue' => Donation::where('status', 'successful')->sum('total_amount'),
            'total_donations' => Donation::where('status', 'successful')->count(),
        ];

        // Latest donations
        $latestDonations = Donation::with(['campaign', 'user'])
            ->where('status', 'successful')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.index', compact('users', 'stats', 'latestDonations'));
    }

public function show(User $user)
{
    $user->load(['donation.campaign', 'volunteer']);
    
    $donationStats = [
        'total_donated' => $user->donation()->where('status', 'successful')->sum('total_amount'),
        'donation_count' => $user->donation()->where('status', 'successful')->count(),
        'first_donation' => $user->donation()->where('status', 'successful')->oldest()->first(),
        'last_donation' => $user->donation()->where('status', 'successful')->latest()->first(),
        'avg_donation' => $user->donation()->where('status', 'successful')->avg('total_amount'),
    ];

    $recentDonations = $user->donation()
        ->with('campaign')
        ->where('status', 'successful')
        ->latest()
        ->limit(10)
        ->get();

    return view('admin.users.show', compact('user', 'donationStats', 'recentDonations'));
}

    public function toggleStatus(User $user)
    {
        $user->update(['active' => !$user->active]);

        return back()->with('success', 'User status updated successfully');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,volunteer,user'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully');
    }

    public function destroy(User $user)
    {
        // Prevent deleting user with donations
        if ($user->donation()->count() > 0) {
            return back()->with('error', 'Cannot delete user with existing donations');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

   public function donors(Request $request)
{
    $query = User::whereHas('donation', function ($q) { 
        $q->where('status', 'successful');
    })
    ->withSum(['donation' => function ($q) { 
        $q->where('status', 'successful');
    }], 'total_amount') 
    ->withCount(['donation' => function ($q) { 
        $q->where('status', 'successful');
    }])
    ->withAvg(['donation' => function ($q) { 
        $q->where('status', 'successful');
    }], 'total_amount'); 


    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    // Filter by donation amount
    if ($request->filled('min_amount')) {
        $query->having('donation_sum_total_amount', '>=', $request->min_amount); 
    }

    // Filter by time period
    if ($request->filled('period')) {
        $donationQuery = function ($q) use ($request) {
            $q->where('status', 'successful');
            switch ($request->period) {
                case 'this_month':
                    $q->whereMonth('created_at', now()->month);
                    break;
                case 'this_year':
                    $q->whereYear('created_at', now()->year);
                    break;
                case 'last_30_days':
                    $q->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        };
        $query->whereHas('donation', $donationQuery); 
    }

    $donors = $query->orderByDesc('donation_sum_total_amount')->paginate(15);

    
    $donorStats = [
        'total_donors' => User::whereHas('donation', function($q) {
            $q->where('status', 'successful');
        })->count(),
        'top_donor_amount' => DB::table('donations')
            ->where('status', 'successful')
            ->selectRaw('SUM(total_amount) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(1)
            ->value('total') ?? 0,
        'avg_donation_amount' => Donation::where('status', 'successful')->avg('total_amount') ?? 0,
    ];

    return view('admin.users.donors', compact('donors', 'donorStats'));
}

    public function subscribers(Request $request)
    {
        $query = Subscriber::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('unsubscribed_at');
            } elseif ($request->status === 'unsubscribed') {
                $query->whereNotNull('unsubscribed_at');
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscribers = $query->latest()->paginate(15);

        $stats = [
            'total_subscribers' => Subscriber::count(),
            'active_subscribers' => Subscriber::whereNull('unsubscribed_at')->count(),
            'unsubscribed' => Subscriber::whereNotNull('unsubscribed_at')->count(),
            'this_month' => Subscriber::where('subscribed_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        return view('admin.users.subscribers', compact('subscribers', 'stats'));
    }

    public function export(Request $request)
    {
        $users = User::with(['donation'])->get();

        $filename = 'users_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Total Donated', 'Donations Count', 'Registered Date']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->active ? 'Active' : 'Inactive',
                    number_format($user->donation()->where('status', 'successful')->sum('total_amount'), 2),
                    $user->donation()->where('status', 'successful')->count(),
                    $user->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}