<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Threads extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'threads';

    protected $fillable = [
        'is_group',
        'title',
        'created_at',
        'updated_at',
    ];

    /**
     * Get all of the users that are assigned to the thread.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'thread_user', 'thread_id', 'user_id');
    }

}
