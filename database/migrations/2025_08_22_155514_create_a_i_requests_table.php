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
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ai_model_id')->constrained('ai_models')->onDelete('cascade');
            $table->string('feature')->index(); // code_review|snippet_gen|error_explain|job_fit|summary|matching
            $table->string('provider_request_id')->nullable()->index();
            $table->string('status')->default('pending'); // pending|succeeded|failed|cancelled
            $table->unsignedInteger('latency_ms')->nullable();
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->integer('cost_cents_estimated')->default(0);
            $table->json('request_payload');   // sanitized prompt + params
            $table->json('response_payload')->nullable(); // sanitized response
            $table->text('error')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_requests');
    }
};
