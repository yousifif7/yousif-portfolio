<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Reply</title></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:30px 0;">
<tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.05);">
        <tr><td style="background:linear-gradient(135deg,#6366f1,#06b6d4);padding:30px;color:#fff;">
            <h1 style="margin:0;font-size:22px;">Hello {{ $contact->name }} 👋</h1>
            <p style="margin:8px 0 0;opacity:0.9;font-size:14px;">Thanks for reaching out. Here's my reply to your message.</p>
        </td></tr>
        <tr><td style="padding:30px;color:#374151;line-height:1.7;">
            <div style="white-space:pre-wrap;">{{ $replyBody }}</div>

            <hr style="margin:30px 0;border:none;border-top:1px solid #e5e7eb;">

            <p style="color:#6b7280;font-size:13px;margin:0;"><strong>Your original message:</strong></p>
            <p style="color:#6b7280;font-size:13px;margin:5px 0 0;"><em>Subject:</em> {{ $contact->subject }}</p>
            <div style="background:#f9fafb;padding:15px;border-radius:8px;color:#6b7280;font-size:13px;margin-top:10px;line-height:1.6;white-space:pre-wrap;">{{ $contact->message }}</div>
        </td></tr>
        <tr><td style="background:#f9fafb;padding:20px;text-align:center;color:#6b7280;font-size:12px;">Sent from {{ config('app.name') }}</td></tr>
    </table>
</td></tr>
</table>
</body>
</html>
