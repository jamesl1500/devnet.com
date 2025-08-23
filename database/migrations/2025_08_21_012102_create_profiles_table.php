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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('years_experience')->unsigned();
            $table->string('seniority')->default('junior');
            $table->string('current_job_title')->nullable();
            $table->enum('looking_for_work', ['yes', 'no', 'maybe'])->default('yes');
            $table->json('languages')->nullable();
            $table->json('skills')->nullable();
            $table->json('frameworks')->nullable();
            $table->string('availability')->default('full_time');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
