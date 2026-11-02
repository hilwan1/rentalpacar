<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TalentOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $talent,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Kata Sandi Akun Talent - RentalPacar',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.talent-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

