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
        Schema::create('user_ai_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique('users')->constrained()->cascadeOnDelete();
            $table->bigInteger('credits_tokens_remaining')->default(0); // tokens or “requests”
            $table->timestamp('period_starts_at')->nullable();
            $table->timestamp('period_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ai_credits');
    }
};
