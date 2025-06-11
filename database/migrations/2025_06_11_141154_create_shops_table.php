<?php

use App\Models\Admin;
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
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnDelete();
            $table->string('subdomain')->unique()->nullable();
            $table->string('name');
            $table->string('province');
            $table->string('prefecture');
            $table->string('town');
            $table->string('address');
            $table->string('phone_number', 20)->nullable();
            $table->boolean('status');
            $table->string('api_key', 50)->unique();
            $table->string('channel_access_token')->nullable();
            $table->string('channel_secret')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
