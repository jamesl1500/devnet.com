<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Privacy_settings extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'privacy_settings';

    protected $fillable = [
        'user_id',
        'show_email',
        'show_phone',
        'show_profile_picture',
        'show_online_status',
        'show_posts_to_public',
        'show_followings_list',
        'show_followers_list',
        'show_profile_information',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the privacy settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}