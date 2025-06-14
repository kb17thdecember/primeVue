<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriber_histories', function (Blueprint $table) {
            $table->dropColumn('subscriber_id');
            $table->text('payment_data')->jsonSerialize();
            $table->integer('token_qty')->default(1000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriber_histories', function (Blueprint $table) {
            $table->string('subscriber_id')->unique();
            $table->dropColumn('payment_data');
            $table->dropColumn('token_qty');
        });
    }
};
