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
            $table->string( 'payment_session_id')->nullable()->change();
            $table->string( 'payment_price_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriber_histories', function (Blueprint $table) {
            $table->string( 'payment_session_id')->change();
            $table->dropColumn( 'payment_price_id');
        });
    }
};
