<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follows extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'follows';

    protected $fillable = [
        'follower_id',
        'followable_id',
        'followable_type',
    ];

    /**
     * Get the user that is the follower.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get followable morph
     */
    public function followable()
    {
        return $this->morphTo();
    }
}
