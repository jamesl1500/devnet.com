<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    // Soft Deletes
    use SoftDeletes;
    
    // Table name
    protected $table = 'comments';

    protected $fillable = [
        'parent_id',
        'user_id',
        'body',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment of the comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comments::class, 'parent_id');
    }

    /**
     * Get the child comments of the comment.
     */
    public function children()
    {
        return $this->hasMany(Comments::class, 'parent_id');
    }    
}
