<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reactions extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'reactions';

    protected $fillable = [
        'user_id',
        'type',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the reaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Morph 
     */
    public function reactable()
    {
        return $this->morphTo();
    }
}
