<?php

namespace Modules\CMS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $content;

    public function __construct($subjectText, $content)
    {
        $this->subjectText = $subjectText;
        $this->content = $content;
    }

    public function build(): self
    {
        return $this->subject($this->subjectText)
            ->markdown('cms::emails.request_key');
    }
}
