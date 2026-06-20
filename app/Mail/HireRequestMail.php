<?php

namespace App\Mail;

use App\Models\HireRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HireRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, string>  $offeringTitles
     */
    public function __construct(
        public HireRequest $hireRequest,
        public array $offeringTitles = [],
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Hire Request from '.$this->hireRequest->name,
            replyTo: [$this->hireRequest->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hire-request',
        );
    }
}
