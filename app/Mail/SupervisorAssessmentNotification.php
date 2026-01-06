<?php

namespace App\Mail;

use App\Models\TrainingNeedsAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupervisorAssessmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $assessment;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct(TrainingNeedsAssessment $assessment, string $token)
    {
        $this->assessment = $assessment;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Training Needs Assessment - Action Required',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.supervisor-assessment-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
