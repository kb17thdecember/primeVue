<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command("app:auto-cancel-subscribe-payment-stripe")->everyThirtyMinutes();
Schedule::command("app:inactive-shop-token-stock-expired")->cron('0 0 * * *');

