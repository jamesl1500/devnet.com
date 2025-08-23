<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread_user extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'thread_user';

    protected $fillable = [
        'thread_id',
        'user_id',
        'role',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the thread that owns the thread_user.
     */
    public function thread()
    {
        return $this->belongsTo(Threads::class, 'thread_id');
    }

    /**
     * Get the user that owns the thread_user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
