<?php

namespace Modules\CMS\Contracts\Services;

interface MailService
{
    /**
     * @param array $data
     * @return void
     */
    public function send(array $data): void;
}