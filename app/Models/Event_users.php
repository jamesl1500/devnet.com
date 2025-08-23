<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event_users extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table name
    protected $table = 'event_users';

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'role',
    ];

    /**
     * Get the event that owns the event_user.
     */
    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    /**
     * Get the user that owns the event_user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}