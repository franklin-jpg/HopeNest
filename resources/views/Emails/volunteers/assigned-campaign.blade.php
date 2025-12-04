<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" text/html; charset=utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); margin: 0; padding: 20px 0; min-height: 100vh; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 3rem 2.5rem; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 50%); }
        .header-content { position: relative; z-index: 1; }
        .success-badge { display: inline-block; background: rgba(255,255,255,0.2); color: white; padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 600; margin-bottom: 1rem; backdrop-filter: blur(10px); }
        .content { padding: 2.5rem; }
        .greeting { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 2rem; border-radius: 16px; border-left: 6px solid #16a34a; margin-bottom: 2rem; }
        .campaign-card { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 2.5rem; border-radius: 16px; border-left: 6px solid #f59e0b; margin: 2rem 0; position: relative; overflow: hidden; }
        .campaign-card::before { content: ''; position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: linear-gradient(to bottom, #f97316, #ea580c); }
        .campaign-card-content { position: relative; z-index: 1; }
        .campaign-title { font-size: 28px; font-weight: 700; color: #92400e; margin: 0 0 0.5rem 0; line-height: 1.3; }
        .campaign-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 1.5rem 0; }
        .meta-item { background: rgba(255,255,255,0.7); padding: 1rem 1.5rem; border-radius: 12px; text-align: center; }
        .meta-icon { display: block; font-size: 2rem; margin-bottom: 0.5rem; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 25px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status-active { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .status-assigned { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 16px 36px; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; margin: 1.5rem 0.75rem 0 0; box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3); transition: all 0.3s ease; }
        .btn:hover { background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4); color: white; text-decoration: none; }
        .btn-secondary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3); }
        .btn-secondary:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4); }
        .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3); }
        .btn-success:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4); }
        .footer { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2.5rem; text-align: center; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1.5rem; margin: 2rem 0; }
        .stat-item { background: rgba(255,255,255,0.6); padding: 1.5rem; border-radius: 12px; text-align: center; }
        .stat-number { font-size: 2.5rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 13px; color: #6b7280; font-weight: 500; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
                <span class="success-badge">
                    <i style="margin-right: 8px;">✅</i>
                    CAMPAIGN ASSIGNMENT
                </span>
                <h1 style="margin: 1.5rem 0 0.5rem 0; font-size: 32px; font-weight: 700; line-height: 1.2;">
                    You're Officially Assigned!
                </h1>
                <p style="margin: 0; opacity: 0.95; font-size: 16px; max-width: 450px; margin-left: auto; margin-right: auto;">
                    Get ready to make a real impact with {{ $campaign->title }}
                </p>
            </div>
        </div>

        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <h2 style="margin: 0 0 0.75rem 0; font-size: 24px; font-weight: 700; color: #166534; display: flex; align-items: center;">
                    <i style="margin-right: 12px; font-size: 1.4em;">🎯</i>
                    Hi {{ $volunteer->user->name }},
                </h2>
                <p style="margin: 0; color: #166534; line-height: 1.7; font-size: 16px;">
                    <strong>Great news!</strong> Our team has selected you for an important mission. 
                    You're now officially part of this campaign!
                </p>
            </div>

            <!-- Campaign Details Card -->
            <div class="campaign-card">
                <div class="campaign-card-content">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                        <h1 class="campaign-title">
                            {{ $campaign->title }}
                        </h1>
                        <span class="status-badge status-assigned">
                            <i style="margin-right: 6px;">🎖️</i>
                            Assigned to You
                        </span>
                    </div>

                    <div class="campaign-meta">
                        <div class="meta-item">
                            <span class="meta-icon" style="color: #f59e0b;">📅</span>
                            <strong>{{ $campaign->start_date?->format('M d, Y') ?? 'TBD' }}</strong>
                            <div style="font-size: 13px; color: #92400e; margin-top: 0.25rem;">Start Date</div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon" style="color: #10b981;">📍</span>
                            <strong>{{ $campaign->location ?? 'Various Locations' }}</strong>
                            <div style="font-size: 13px; color: #065f46; margin-top: 0.25rem;">Location</div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon" style="color: #3b82f6;">👥</span>
                            <strong>{{ $campaign->volunteers_needed ?? 'Multiple' }}</strong>
                            <div style="font-size: 13px; color: #1e40af; margin-top: 0.25rem;">Volunteers Needed</div>
                        </div>
                    </div>

                    @if($campaign->description)
                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.7); border-radius: 12px; border-left: 4px solid #f97316;">
                        <h3 style="margin: 0 0 1rem 0; font-size: 18px; font-weight: 600; color: #92400e; display: flex; align-items: center;">
                            <i style="margin-right: 10px; font-size: 1.2em;">📋</i>
                            Campaign Overview
                        </h3>
                        <p style="margin: 0; color: #92400e; line-height: 1.7;">
                            {{ Str::limit($campaign->description, 250) }}
                        </p>
                        @if(strlen($campaign->description) > 250)
                            <p style="margin: 0.75rem 0 0 0; font-size: 14px; color: #d97706;">
                                <i style="margin-right: 6px;">👉</i>
                                <a href="{{ route('campaigns.show', $campaign) }}" style="color: #f97316; text-decoration: none; font-weight: 500;">Read full details</a>
                            </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 3rem 0;">
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-success">
                        <i style="margin-right: 8px;">👀</i>
                        View Campaign Details
                    </a>
                    <a href="{{ route('volunteer.dashboard') }}" class="btn">
                        <i style="margin-right: 8px;">📊</i>
                        My Dashboard
                    </a>
                </div>
                <a href="{{ route('volunteer.log-hours') }}" class="btn btn-secondary" style="font-size: 15px; padding: 14px 28px;">
                    <i style="margin-right: 8px;">⏱️</i>
                    Log My First Hours
                </a>
            </div>

            <!-- Volunteer Stats -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" style="color: #10b981;">{{ $volunteer->campaigns()->wherePivot('status', 'active')->count() + 1 }}</div>
                    <div class="stat-label">Active Campaigns</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" style="color: #f97316;">{{ $volunteer->hours()->where('status', 'approved')->sum('hours') }}</div>
                    <div class="stat-label">Total Hours</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" style="color: #3b82f6;">{{ $volunteer->id }}</div>
                    <div class="stat-label">Campaign #{{ $campaign->id }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                    <i style="font-size: 20px; color: white;">🎖️</i>
                </div>
                <div style="text-align: center;">
                    <p style="margin: 0 0 0.25rem 0; font-size: 16px; font-weight: 700; color: #059669;">
                        Welcome to the Team!
                    </p>
                    <p style="margin: 0; font-size: 14px; color: #6b7280;">
                        {{ config('app.name') }} Campaign Crew
                    </p>
                </div>
            </div>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 2rem 0;">
            <p style="margin: 0 0 0.75rem 0; font-size: 14px; color: #9ca3af;">
                <strong>What's next?</strong><br>
                Review campaign details → Log your hours → Make an impact!
            </p>
            <p style="margin: 0; font-size: 13px; color: #d1d5db;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>