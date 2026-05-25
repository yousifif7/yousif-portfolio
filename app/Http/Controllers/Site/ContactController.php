<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('site.contact');
    }

    public function store(ContactFormRequest $request)
    {
        $data = $request->validated();
        unset($data['website']); // honeypot field

        $contact = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        // Try sending an admin notification email — never block the user if SMTP fails.
        try {
            $adminEmail = config('services.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ContactFormMail($contact));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send contact notification: '.$e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Thanks for reaching out! Your message has been sent, I will get back to you soon.');
    }
}
