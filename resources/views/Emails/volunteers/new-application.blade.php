<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" text/html; charset=utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 2rem; text-align: center; }
        .content { padding: 2.5rem; }
        .applicant-info { background: #fef3c7; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid #f59e0b; }
        .motivation { background: #f8fafc; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 2rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; margin: 1rem 0; }
        .btn:hover { background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); }
        .footer { background: #f8fafc; padding: 1.5rem; text-align: center; font-size: 14px; color: #6b7280; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                <i style="margin-right: 12px;">👋</i>
                New Volunteer Application
            </h1>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">A new volunteer wants to join your team!</p>
        </div>

        <div class="content">
            <div class="applicant-info">
                <h2 style="margin: 0 0 1rem 0; font-size: 20px; font-weight: 600; color: #92400e;">
                    <i style="margin-right: 8px;">👤</i>
                    {{ $volunteer->user->name }}
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 15px;">
                    <div>
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $volunteer->user->email }}" style="color: #1f2937;">{{ $volunteer->user->email }}</a>
                    </div>
                    <div>
                        <strong>Phone:</strong><br>
                        {{ $volunteer->phone }}
                    </div>
                    <div>
                        <strong>Applied:</strong><br>
                        {{ $volunteer->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        <span class="status-badge badge-pending">
                            <i style="margin-right: 4px;">⏳</i>
                            Pending Review
                        </span>
                    </div>
                </div>
            </div>

            @if($volunteer->skills)
            <div style="background: #dbeafe; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #2563eb; margin-bottom: 2rem;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 16px; font-weight: 600; color: #1e40af;">
                    <i style="margin-right: 8px;">⚡</i>Skills & Experience
                </h3>
                <p style="margin: 0; color: #1e40af;">{{ Str::limit($volunteer->skills, 150) }}</p>
            </div>
            @endif

            <div class="motivation">
                <h3 style="margin: 0 0 1rem 0; font-size: 18px; font-weight: 600; color: #1e40af;">
                    <i style="margin-right: 8px;">💬</i>Why They Want to Volunteer
                </h3>
                <p style="margin: 0; line-height: 1.7; color: #334155;">
                    "{{ Str::limit($volunteer->motivation, 250) }}"
                </p>
                @if(strlen($volunteer->motivation) > 250)
                    <p style="margin: 0.5rem 0 0 0; font-size: 14px; color: #64748b;">
                        <i style="margin-right: 4px;">📝</i>Read full motivation in admin panel
                    </p>
                @endif
            </div>

            <div style="text-align: center; margin: 2rem 0;">
                <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="btn">
                    <i style="margin-right: 8px;">👀</i>
                    Review Application Now
                </a>
            </div>

            <div style="background: #f3f4f6; padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
                <p style="margin: 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                    <strong>Quick Actions Available:</strong><br>
                    • Approve volunteer<br>
                    • Reject with reason<br>
                    • View full application details<br>
                    • Assign to campaigns
                </p>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0 0 0.5rem 0;">
                Sent by <strong>{{ config('app.name') }}</strong>
            </p>
            <p style="margin: 0; font-size: 12px; opacity: 0.7;">
                This is an automated notification. Please don't reply to this email.
            </p>
        </div>
    </div>
</body>
</html>