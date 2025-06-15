<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Mail\ReminderMail;
use App\Models\Setting;
use App\Models\Shop;
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
                $shopMustRemaining = Shop::query()
                    ->with('admin')
                    ->where('current_token_qty', '<=', $setting->remaining)
                    ->where('token_expired_date', '>=', now()->format('Y-m-d'))
                    ->get();

                foreach ($shopMustRemaining as $shop) {
                    if (!isset($shop->admin) || !Role::SHOP->is($shop->role)) {
                        continue;
                    }

                    Mail::to($shop->admin->email)->send(new ReminderMail($setting));
                }
            }

            return true;
        } catch (\Exception $exception) {
            Log::error("AUTO-REMAINING-SHOP: Error " . $exception->getMessage());
            Log::error($exception);

            return;
        }
    }
}

