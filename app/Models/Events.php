<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Soft deletes
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    // Soft deletes
    use SoftDeletes;

    // Table name
    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'website',
        'capacity',
        'banner_id',
        'creator_id'
    ];

    /**
     * Get the comments for the event.
     */
    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    /**
     * Get the users for the event.
     */
    public function users()
    {
        return $this->hasMany(Event_users::class, 'event_id');
    }
}
