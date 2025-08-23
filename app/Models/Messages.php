<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messages extends Model
{
    // Soft delete
    use SoftDeletes;

    protected $table = 'messages';

    protected $fillable = [
        'thread_id',
        'user_id',
        'body',
        'attachments',
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the thread associated with the message.
     */
    public function thread()
    {
        return $this->belongsTo(Threads::class, 'thread_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
