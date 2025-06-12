<?php

use App\Enums\ProductType;
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
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->integer('type')->default(ProductType::ONE_TIME);

            $table->dropColumn('shop_id');
            $table->dropColumn('category_id');
            $table->dropColumn('brand_id');
            $table->dropColumn('display_order');
            $table->dropColumn('discount_code');
            $table->dropColumn('discount');
            $table->dropColumn('tag');
            $table->dropColumn('status');
            $table->dropColumn('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->integer('display_order')->nullable();
            $table->string('discount_code')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('status')->default(0);
            $table->integer('quantity');
        });
    }
};
