<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $resetUrl,
        public ?string $subjectLine = null,
        public ?string $bodyText = null,
        public ?string $fromName = null,
    ) {}

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS');

        return new Envelope(
            from: $fromAddress ? new Address($fromAddress, $this->fromName ?: (\App\Models\SiteSetting::first()?->title ?: config('app.name'))) : null,
            subject: $this->subjectLine ?: __('main.reset_password'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-reset',
            with: [
                'resetUrl' => $this->resetUrl,
                'bodyText' => $this->bodyText ?: __('main.reset_email_body'),
            ],
        );
    }
}
