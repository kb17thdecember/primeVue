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
        Schema::create('subscriber_histories', function (Blueprint $table) {
            $table->id();
            $table->string('subscriber_id')->unique();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('shop_id');
            $table->integer('price');
            $table->integer('type')->default(\App\Enums\ProductType::ONE_TIME);
            $table->integer('payment_status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_histories');
    }
};
