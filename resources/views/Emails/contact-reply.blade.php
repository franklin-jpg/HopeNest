{{-- resources/views/emails/contact-reply.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re: {{ ucfirst(str_replace('_', ' ', $contact->subject)) }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%); color: white; padding: 30px 20px; text-align: center; }
        .content { padding: 30px 20px; }
        .reply-box { background: #fff8f5; padding: 20px; border-left: 4px solid #ff5722; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>We’ve replied to your message</h1>
        </div>

        <div class="content">
            <p>Dear {{ $contact->first_name }},</p>

            <p>Thank you for contacting HopeNest. Here is our reply to your message:</p>

            <div class="reply-box">
                {!! nl2br(e($replyMessage)) !!}
            </div>

            <p>If you have any further questions, feel free to reply to this email or contact us again.</p>

            <p>Warm regards,<br>
               <strong>{{ $adminName }}</strong><br>
               HopeNest Support Team
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HopeNest. All rights reserved.</p>
        </div>
    </div>
</body>
</html>