<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\NotificationsSettings;

class Notifications_settings extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'notifications_settings';

    // Library
    public static $notificationsSettingsLib = NotificationsSettings::class;

    // Fillable
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