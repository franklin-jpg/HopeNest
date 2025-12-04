<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" text/html; charset=utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); margin: 0; padding: 20px 0; min-height: 100vh; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 2.5rem; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -50%; right: -50%; width: 100%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 20px 20px; }
        .header-content { position: relative; z-index: 1; }
        .content { padding: 2.5rem; }
        .greeting { background: #fffbeb; padding: 1.5rem 2rem; border-radius: 12px; border-left: 5px solid #f59e0b; margin-bottom: 2rem; }
        .message-content { background: #f8fafc; padding: 2rem; border-radius: 12px; border-left: 5px solid #3b82f6; margin: 2rem 0; line-height: 1.7; }
        .message-content h1, .message-content h2, .message-content h3 { color: #1f2937; margin-top: 0; }
        .message-content h1 { font-size: 28px; border-bottom: 3px solid #f97316; padding-bottom: 0.5rem; }
        .message-content h2 { font-size: 22px; color: #f97316; }
        .message-content h3 { font-size: 18px; color: #3b82f6; }
        .message-content ul, .message-content ol { padding-left: 1.5rem; margin: 1rem 0; }
        .message-content li { margin: 0.5rem 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px; margin: 1.5rem 0; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3); transition: all 0.3s ease; }
        .btn:hover { background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4); color: white; text-decoration: none; }
        .btn-secondary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); }
        .btn-secondary:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); }
        .footer { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; text-align: center; font-size: 14px; color: #6b7280; }
        .announcement-badge { display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 8px 20px; border-radius: 25px; font-size: 13px; font-weight: 600; margin-bottom: 1rem; box-shadow: 0 2px 10px rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📢</div>
                <h1 style="margin: 0; font-size: 32px; font-weight: 700; line-height: 1.2;">
                    Important Update
                </h1>
                <p style="margin: 0.75rem 0 0 0; opacity: 0.95; font-size: 16px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    {{ $emailSubject }}
                </p>
            </div>
        </div>

        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 22px; font-weight: 600; color: #92400e; display: flex; align-items: center;">
                    <i style="margin-right: 12px; font-size: 1.3em;">👋</i>
                    Hi {{ $volunteer->user->name }},
                </h2>
                <p style="margin: 0; color: #b45309; line-height: 1.6; font-size: 15px;">
                    We have an important message for you from the {{ config('app.name') }} team!
                </p>
            </div>

            <!-- Announcement Badge -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <span class="announcement-badge">
                    <i style="margin-right: 6px;">⭐</i>
                    Official Announcement
                </span>
            </div>

            <!-- Main Message Content -->
            <div class="message-content">
                {!! nl2br(e($emailMessage)) !!}
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 2.5rem 0;">
                <a href="{{ route('volunteer.dashboard') }}" class="btn">
                    <i style="margin-right: 8px;">📊</i>
                    Go to My Dashboard
                </a>
                
                @if($secondaryUrl ?? false)
                <br>
                <a href="{{ $secondaryUrl }}" class="btn btn-secondary" style="margin-top: 1rem;">
                    <i style="margin-right: 8px;">🔗</i>
                    {{ $secondaryButtonText ?? 'Learn More' }}
                </a>
                @endif
            </div>

            <!-- Quick Stats -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; margin: 2rem 0; padding: 1.5rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #ea580c;">{{ $volunteer->hours()->where('status', 'approved')->sum('hours') }}</div>
                    <div style="font-size: 13px; color: #92400e; font-weight: 500;">Total Hours</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #059669;">{{ $volunteer->campaigns()->wherePivot('status', 'active')->count() }}</div>
                    <div style="font-size: 13px; color: #065f46; font-weight: 500;">Active Campaigns</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #3b82f6;">{{ $volunteer->hours()->where('status', 'pending')->count() }}</div>
                    <div style="font-size: 13px; color: #1e40af; font-weight: 500;">Pending Reviews</div>
                </div>
            </div>

            <!-- Call to Action -->
            <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 1.5rem; border-radius: 12px; text-align: center; border-left: 5px solid #16a34a;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 18px; font-weight: 600; color: #166534; display: flex; align-items: center; justify-content: center;">
                    <i style="margin-right: 10px;">🚀</i>
                    Ready to make an impact?
                </h3>
                <p style="margin: 0; color: #166534; font-size: 15px;">
                    Log your next hours or join a new campaign today!
                </p>
            </div>
        </div>

        <div class="footer">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                    <i style="font-size: 18px; color: white;">🤝</i>
                </div>
                <div>
                    <p style="margin: 0 0 0.25rem 0; font-size: 15px; font-weight: 600; color: #1f2937;">
                        {{ config('app.name') }} Team
                    </p>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">
                        Making a difference, together
                    </p>
                </div>
            </div>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">
            <p style="margin: 0 0 0.5rem 0; font-size: 13px; color: #9ca3af;">
                <strong>You received this email</strong> because you're an active volunteer with us.
            </p>
            <p style="margin: 0; font-size: 12px; color: #d1d5db;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved. | 
                <a href="{{ route('volunteer.status') }}" style="color: #f97316; text-decoration: none;">View Status</a>
            </p>
        </div>
    </div>
</body>
</html>