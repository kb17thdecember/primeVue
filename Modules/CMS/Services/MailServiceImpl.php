<?php

namespace Modules\CMS\Services;

use Illuminate\Support\Facades\Mail;
use Modules\CMS\Contracts\Services\MailService;
use Modules\CMS\Emails\SendCustomMail;

class MailServiceImpl implements MailService
{
    /**
     * @param array $data
     * @return void
     */
    public function send(array $data): void
    {
        Mail::to($data['to'])->send(new SendCustomMail($data['subject'], $data['content']));
    }
}
