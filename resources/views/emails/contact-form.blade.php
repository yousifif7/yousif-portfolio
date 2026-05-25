<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>New Contact Message</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:30px 0;">
<tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.05);">
        <tr><td style="background:linear-gradient(135deg,#6366f1,#06b6d4);padding:30px;color:#fff;">
            <h1 style="margin:0;font-size:22px;">📬 New Contact Form Submission</h1>
            <p style="margin:8px 0 0;opacity:0.9;font-size:14px;">You received a new message from your portfolio website.</p>
        </td></tr>
        <tr><td style="padding:30px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr><td style="padding:10px 0;border-bottom:1px solid #eee;"><strong style="color:#374151;display:inline-block;width:90px;">From:</strong> {{ $contact->name }}</td></tr>
                <tr><td style="padding:10px 0;border-bottom:1px solid #eee;"><strong style="color:#374151;display:inline-block;width:90px;">Email:</strong> <a href="mailto:{{ $contact->email }}" style="color:#6366f1;">{{ $contact->email }}</a></td></tr>
                <tr><td style="padding:10px 0;border-bottom:1px solid #eee;"><strong style="color:#374151;display:inline-block;width:90px;">Subject:</strong> {{ $contact->subject }}</td></tr>
                <tr><td style="padding:10px 0;"><strong style="color:#374151;display:inline-block;width:90px;">Date:</strong> {{ $contact->created_at->format('M d, Y H:i') }}</td></tr>
            </table>
            <h3 style="color:#374151;margin-top:25px;margin-bottom:10px;font-size:16px;">Message:</h3>
            <div style="background:#f9fafb;padding:20px;border-radius:8px;border-left:4px solid #6366f1;color:#374151;line-height:1.7;white-space:pre-wrap;">{{ $contact->message }}</div>
            <div style="margin-top:25px;text-align:center;">
                <a href="{{ url('/admin/messages/' . $contact->id) }}" style="background:#6366f1;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;display:inline-block;font-weight:600;">Reply in Admin Panel</a>
            </div>
        </td></tr>
        <tr><td style="background:#f9fafb;padding:20px;text-align:center;color:#6b7280;font-size:12px;">This is an automated message from your portfolio contact form.</td></tr>
    </table>
</td></tr>
</table>
</body>
</html>
