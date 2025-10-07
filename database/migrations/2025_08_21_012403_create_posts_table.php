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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->text('body');
            $table->enum('type', ['text', 'image', 'video', 'question', 'changelog'])->default('text');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'followers'])->default('public');
            $table->foreignId('cover_id')->nullable()->constrained('files')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('original_post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
