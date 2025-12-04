<?php


namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\VolunteerHour;
use Illuminate\Http\Request;
use App\Mail\NewVolunteerApplication;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;  
use Illuminate\Foundation\Validation\ValidatesRequests;  
use Illuminate\Routing\Controller;

class VolunteerApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        // Check if user already has an application
        $existingApplication = Volunteer::where('user_id', auth()->id())->first();

        if ($existingApplication) {
            return redirect()->route('volunteer.status')->with('info', 'You already have a volunteer application.');
        }

        return view('applyVolunteer.apply');
    }

    public function store(Request $request)
    {
        // Check if user already applied
        if (Volunteer::where('user_id', auth()->id())->exists()) {
            return redirect()->route('volunteer.status')->with('error', 'You have already submitted a volunteer application.');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'skills' => 'nullable|string|max:1000',
            'availability' => 'required|string|max:500',
            'motivation' => 'required|string|max:1000',
        ]);

        $volunteer = Volunteer::create([
            'user_id' => auth()->id(),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'skills' => $validated['skills'],
            'availability' => $validated['availability'],
            'motivation' => $validated['motivation'],
            'status' => 'pending',
        ]);

        // Notify admins about new application
               $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewVolunteerApplication($volunteer));
        }


        
        return redirect()->route('volunteer.status')->with('success', 'Your volunteer application has been submitted successfully!');
    }

    public function status()
    {
        $volunteer = Volunteer::with(['campaigns', 'hours'])->where('user_id', auth()->id())->first();

        if (!$volunteer) {
            return redirect()->route('volunteer.apply')->with('info', 'You have not applied to become a volunteer yet.');
        }

        return view('applyVolunteer.status', compact('volunteer'));
    }

    public function dashboard()
    {
       $volunteer = Volunteer::with([
            'campaigns',           //  directly loads Campaign models
            'hours.campaign'       //  VolunteerHour → belongsTo Campaign
        ])
        ->where('user_id', auth()->id())
        ->firstOrFail();

        if (!$volunteer->isApproved()) {
            return redirect()->route('volunteer.status');
        }

        $totalHours = $volunteer->hours()->where('status', 'approved')->sum('hours');
        $pendingHours = $volunteer->hours()->where('status', 'pending')->sum('hours');
        $activeCampaigns = $volunteer->campaigns()->wherePivot('status', 'active')->count();

        return view('Volunteer.dashboard', compact('volunteer', 'totalHours', 'pendingHours', 'activeCampaigns'));
    }

    public function logHours(Request $request)
    {
        $volunteer = Volunteer::where('user_id', auth()->id())->firstOrFail();

        if (!$volunteer->isApproved()) {
            return redirect()->back()->with('error', 'Only approved volunteers can log hours.');
        }

        $validated = $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:24',
            'description' => 'required|string|max:500',
        ]);

        VolunteerHour::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $validated['campaign_id'],
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Hours logged successfully! Awaiting admin approval.');
    }
}

