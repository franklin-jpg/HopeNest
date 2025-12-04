<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" text/html; charset=utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; background: #f9fafb; margin: 0; padding: 20px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 2.5rem; text-align: center; }
        .content { padding: 2.5rem; }
        .greeting { background: #fef2f2; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #ef4444; margin-bottom: 2rem; }
        .rejection-reason { background: #fee2e2; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #dc2626; margin: 2rem 0; }
        .encouragement { background: #dbeafe; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #2563eb; margin: 2rem 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; margin: 1rem 0; }
        .btn:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .footer { background: #f8fafc; padding: 2rem; text-align: center; font-size: 14px; color: #6b7280; }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📋</div>
            <h1 style="margin: 0; font-size: 28px; font-weight: 700; line-height: 1.3;">
                Application Update
            </h1>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 16px;">
                We appreciate your interest in volunteering
            </p>
        </div>

        <div class="content">
            <div class="greeting">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 22px; font-weight: 600; color: #991b1b;">
                    <i style="margin-right: 12px; font-size: 1.2em;">👋</i>
                    Dear {{ $volunteer->user->name }},
                </h2>
                <p style="margin: 0; color: #7f1d1d; line-height: 1.6;">
                    Thank you for taking the time to apply to volunteer with <strong>{{ config('app.name') }}</strong>. 
                    We truly appreciate your desire to make a difference in our community.
                </p>
            </div>

            <div class="rejection-reason">
                <h3 style="margin: 0 0 1rem 0; font-size: 18px; font-weight: 600; color: #991b1b; display: flex; align-items: center;">
                    <i style="margin-right: 12px; font-size: 1.3em;">⚠️</i>
                    Application Decision
                </h3>
                
                <div style="background: white; padding: 1.5rem; border-radius: 8px; border: 2px solid #fecaca;">
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <span class="status-badge" style="margin-right: 12px;">
                            <i style="margin-right: 6px;">❌</i>
                            Not Approved
                        </span>
                        <span style="font-size: 16px; font-weight: 600; color: #dc2626;">
                            {{ $volunteer->created_at->format('F j, Y') }}
                        </span>
                    </div>
                    
                    @if($volunteer->rejection_reason)
                        <div style="margin-top: 1.5rem;">
                            <p style="margin: 0 0 1rem 0; font-weight: 600; color: #991b1b;">
                                <i style="margin-right: 8px;">📝</i>Reason for Decision:
                            </p>
                            <p style="margin: 0; color: #7f1d1d; line-height: 1.7; padding: 1rem; background: #fdf2f2; border-radius: 8px; border-left: 4px solid #fca5a5;">
                                {{ $volunteer->rejection_reason }}
                            </p>
                        </div>
                    @else
                        <p style="margin: 0; color: #7f1d1d; font-style: italic;">
                            After careful consideration, we determined this wasn't the right fit at this time.
                        </p>
                    @endif
                </div>
            </div>

            <div class="encouragement">
                <h3 style="margin: 0 0 1rem 0; font-size: 18px; font-weight: 600; color: #1e40af; display: flex; align-items: center;">
                    <i style="margin-right: 12px; font-size: 1.3em;">💙</i>
                    We'd Love to Stay Connected
                </h3>
                <p style="margin: 0; color: #1e40af; line-height: 1.7;">
                    Your passion for community service is inspiring! We encourage you to:
                </p>
                <ul style="margin: 1rem 0 0 0; padding-left: 1.5rem; color: #1e40af; line-height: 1.6;">
                    <li>📧 Stay subscribed to our newsletter for future opportunities</li>
                    <li>🌐 Follow us on social media for updates</li>
                    <li>💝 Consider other ways to support our mission</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 2.5rem 0;">
                <a href="{{ route('volunteer.status') }}" class="btn">
                    <i style="margin-right: 8px;">👀</i>
                    View My Status
                </a>
            </div>

            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; text-align: center;">
                <p style="margin: 0 0 1rem 0; font-size: 15px; color: #374151; font-weight: 500;">
                    <i style="margin-right: 8px;">💌</i>
                    Thank you again for your interest!
                </p>
                <p style="margin: 0; font-size: 14px; color: #6b7280;">
                    The {{ config('app.name') }} Team
                </p>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0 0 0.5rem 0; font-size: 13px; color: #9ca3af;">
                <strong>{{ config('app.name') }}</strong> | Making a difference, together
            </p>
            <p style="margin: 0; font-size: 12px; opacity: 0.7;">
                This is an automated notification. Please don't reply to this email.
            </p>
            <p style="margin: 1rem 0 0 0; font-size: 11px; color: #d1d5db;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>