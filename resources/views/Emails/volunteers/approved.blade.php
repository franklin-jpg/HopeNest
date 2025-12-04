<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" text/html; charset=utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); margin: 0; padding: 20px 0; min-height: 100vh; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 3rem 2.5rem; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 30px 30px; animation: sparkle 20s linear infinite; }
        @keyframes sparkle { 0% { transform: translate(0,0); } 100% { transform: translate(-30px,-30px); } }
        .header-content { position: relative; z-index: 1; }
        .success-badge { display: inline-block; background: rgba(255,255,255,0.25); color: white; padding: 12px 28px; border-radius: 50px; font-size: 15px; font-weight: 700; margin-bottom: 1rem; backdrop-filter: blur(10px); }
        .content { padding: 2.5rem; }
        .greeting { background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%); padding: 2rem; border-radius: 16px; border-left: 6px solid #f59e0b; margin-bottom: 2.5rem; }
        .welcome-card { background: linear-gradient(135deg, #ecfdf5 0%, #bbf7d0 100%); padding: 2.5rem; border-radius: 16px; border-left: 6px solid #10b981; margin: 2rem 0; }
        .next-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin: 2.5rem 0; }
        .step { background: white; padding: 1.5rem; border-radius: 14px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 2px solid #fed7aa; }
        .step-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .step-title { font-weight: 700; color: #92400e; margin: 0.5rem 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 14px; font-weight: 700; font-size: 18px; margin: 1.5rem 0; box-shadow: 0 8px 25px rgba(249, 115, 22, 0.35); transition: all 0.3s ease; }
        .btn:hover { background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(249, 115, 22, 0.45); color: white; text-decoration: none; }
        .footer { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2.5rem; text-align: center; }
        .confetti { font-size: 3.5rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div class="confetti">Congratulations!</div>
                <span class="success-badge">
                    APPROVED & WELCOME!
                </span>
                <h1 style="margin: 1rem 0 0.5rem 0; font-size: 36px; font-weight: 800; line-height: 1.2;">
                    You're Officially a Volunteer!
                </h1>
                <p style="margin: 0; opacity: 0.95; font-size: 17px; max-width: 480px; margin-left: auto; margin-right: auto;">
                    Welcome to the {{ config('app.name') }} family — we're so excited to have you!
                </p>
            </div>
        </div>

        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <h2 style="margin: 0 0 0.75rem 0; font-size: 26px; font-weight: 700; color: #92400e; display: flex; align-items: center;">
                    Dear {{ $volunteer->user->name }},
                </h2>
                <p style="margin: 0; color: #b45309; line-height: 1.7; font-size: 17px;">
                    <strong>Congratulations!</strong> Your volunteer application has been <strong>fully approved</strong>. 
                    You're now an official member of our volunteer team!
                </p>
            </div>

            <!-- Welcome Message -->
            <div class="welcome-card">
                <h3 style="margin: 0 0 1rem 0; font-size: 22px; font-weight: 700; color: #166534; text-align: center;">
                    Welcome aboard! We're thrilled to have you
                </h3>
                <p style="margin: 0; color: #166534; line-height: 1.8; font-size: 16px; text-align: center;">
                    Your passion and dedication mean the world to us. Together, we'll create real change in our community.
                </p>
            </div>

            <!-- What's Next? -->
            <div style="margin: 2.5rem 0;">
                <h3 style="text-align: center; font-size: 24px; font-weight: 700; color: #ea580c; margin: 0 0 2rem 0;">
                    What's Next?
                </h3>
                <div class="next-steps">
                    <div class="step">
                        <div class="step-icon">Login</div>
                        <div class="step-title">Access Your Dashboard</div>
                        <p style="margin: 0.5rem 0 0; font-size: 14px; color: #6b7280;">Log in anytime to manage your profile</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">Campaigns</div>
                        <div class="step-title">Join Campaigns</div>
                        <p style="margin: 0.5rem 0 0; font-size: 14px; color: #6b7280;">Get assigned to meaningful projects</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">Hours</div>
                        <div class="step-title">Log Your Hours</div>
                        <p style="margin: 0.5rem 0 0; font-size: 14px; color: #6b7280;">Track every minute you contribute</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">Impact</div>
                        <div class="step-title">Make a Difference</div>
                        <p style="margin: 0.5rem 0 0; font-size: 14px; color: #6b7280;">Help change lives in our community</p>
                    </div>
                </div>
            </div>

            <!-- Main Action Button -->
            <div style="text-align: center; margin: 3rem 0;">
                <a href="{{ route('volunteer.dashboard') }}" class="btn">
                    Go to My Dashboard
                </a>
            </div>

            <!-- Extra Links -->
            <div style="text-align: center; margin: 2rem 0;">
                <p style="margin: 0 0 1rem 0; color: #6b7280; font-size: 15px;">
                    Or quickly access:
                </p>
                <a href="{{ route('volunteer.status') }}" style="color: #f97316; text-decoration: none; margin: 0 1rem; font-weight: 500;">View Status</a>
                <span style="color: #9ca3af;">•</span>
                <a href="{{ route('volunteer.apply') }}" style="color:-#f97316; text-decoration: none; margin: 0 1rem; font-weight: 500;">Update Profile</a>
            </div>
        </div>

        <div class="footer">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);">
                    <span style="font-size: 28px;">Welcome</span>
                </div>
                <div>
                    <p style="margin: 0 0 0.25rem 0; font-size: 18px; font-weight: 700; color: #ea580c;">
                        {{ config('app.name') }} Team
                    </p>
                    <p style="margin: 0; font-size: 15px; color: #6b7280;">
                        Thank you for joining us!
                    </p>
                </div>
            </div>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 2rem 0;">
            <p style="margin: 0 0 0.75rem 0; font-size: 14px; color: #9ca3af;">
                You can now log hours, join campaigns, and track your impact.
            </p>
            <p style="margin: 0; font-size: 13px; color: #d1d5db;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>