<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'pages';

    protected $fillable = [
        'owner_id',
        'title',
        'slug',
        'headline',
        'description',
        'page_avatar_id',
        'cover_image_id',
        'is_published',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the owner that owns the page.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Page Avatar
     */
    public function pageAvatar()
    {
        return $this->belongsTo(Files::class, 'page_avatar_id');
    }

    /**
     * Cover Image
     */
    public function coverImage()
    {
        return $this->belongsTo(Files::class, 'cover_image_id');
    }
}