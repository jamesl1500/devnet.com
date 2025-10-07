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
        Schema::create('privacy_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('show_email')->default(false);
            $table->boolean('show_phone')->default(false);
            $table->boolean('show_profile_picture')->default(true);
            $table->boolean('show_online_status')->default(true);
            $table->boolean('show_posts_to_public')->default(true);
            $table->boolean('show_followings_list')->default(true);
            $table->boolean('show_followers_list')->default(true);
            $table->boolean('show_profile_information')->default(true);
            $table->enum('allow_messages_from', ['everyone', 'friends', 'no_one'])->default('friends');
            $table->boolean('allow_tagging')->default(false);
            $table->boolean('search_visibility')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_settings');
    }
};
