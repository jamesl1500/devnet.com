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
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('openai');
            $table->string('code')->index();
            $table->string('mode')->default('response');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('input_cost_cents_per_1k')->default(0);
            $table->unsignedInteger('output_cost_cents_per_1k')->default(0);
            $table->json('capabilities')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
