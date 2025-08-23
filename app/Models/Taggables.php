<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taggables extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'taggables';

    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the tag that owns the taggable.
     */
    public function tag()
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }

    /**
     * Get the parent taggable model (post, snippet, etc.).
     */
    public function taggable()
    {
        return $this->morphTo();
    }
}
