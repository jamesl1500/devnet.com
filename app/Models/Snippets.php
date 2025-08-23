<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snippets extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'snippets';

    protected $fillable = [
        'user_id',
        'title',
        'language',
        'code',
        'description',
        'is_public',
        'version',
        'forked_from_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the snippet.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the snippet that this snippet is forked from.
     */
    public function forkedFrom()
    {
        return $this->belongsTo(Snippets::class, 'forked_from_id');
    }
}
