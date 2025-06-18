<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\SubscriberHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCancelSubscribePaymentStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-cancel-subscribe-payment-stripe';

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
            DB::beginTransaction();
            $collectionSubscriberPaymentStripePending = SubscriberHistory::query()
                ->where('payment_status', PaymentStatus::PENDING)
                ->where('created_at', '<=', now()->subMinutes(30)->format('Y-m-d H:i:s'))
                ->get();

            foreach ($collectionSubscriberPaymentStripePending as $subscriberPaymentStripePending) {
                $subscriberPaymentStripePending->payment_status = PaymentStatus::SYSTEM_CANCEL->value;
                $subscriberPaymentStripePending->save();

                Log::info("AUTO-CANCEL-SUBSCRIBE-PAYMENT-STRIPE: Auto cancel stripe payment because not finished after 30 minutes: subscribe_history_id = " . $subscriberPaymentStripePending->id);
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            Log::error("AUTO-CANCEL-SUBSCRIBE-PAYMENT-STRIPE: Error " . $exception->getMessage());
            Log::error($exception);

            return;
        }
    }
}
