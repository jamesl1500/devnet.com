<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications_settings extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'notifications_settings';

    protected $fillable = [
        'user_id',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the notification settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}