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
        Schema::create('plan_entitlements', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_price_id')->unique();   // price_xxx
            $table->string('name');                        // Pro Monthly, Team Monthly
            $table->json('features'); // { "ai": {"included_tokens": 5_000_000, "overage_meter": "ai_tokens"}, "placement": true }
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_entitlements');
    }
};
