<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected Setting $setting)
    {
        //
    }

    /**
     * @return $this
     */
    public function build(): static
    {
        $remainingQtyVariable = config('mail.remaining_templates.variables.remaining_qty');
        $mailTemplate = str_replace($remainingQtyVariable, $this->setting->remaining, $this->setting->mail_template);

        return $this->subject($this->setting->mail_subject)
            ->view('emails.remaining', compact('mailTemplate'));
    }
}
