<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'type',
        'status',
        'visibility',
        'attachments',
        'cover_id',
        'group_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the group that owns the post.
     */
    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    /**
     * Cover
     */
    public function cover()
    {
        return $this->belongsTo(Files::class, 'cover_id');
    }

    /**
     * Get comments for the post morph.
     */
    public function comments()
    {
        return $this->morphMany(Comments::class, 'commentable');
    }
}