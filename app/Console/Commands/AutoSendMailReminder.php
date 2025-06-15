<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoSendMailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-send-mail-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $setting = Setting::query()->first();

            if (isset($setting) && $setting->remaining > 0) {
                Mail::to('ziidamshop@gmail.com')->send(new ReminderMail($setting));
            }

            return true;
        } catch (\Exception $exception) {
            Log::error("AUTO-CANCEL-SUBSCRIBE-PAYMENT-STRIPE: Error " . $exception->getMessage());
            Log::error($exception);

            return;
        }
    }
}

