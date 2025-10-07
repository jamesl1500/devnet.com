<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\SecuritySettings;

class Security_settings extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'security_settings';

    // Library
    public static $securitySettingsLib = SecuritySettings::class;

    // Fillable
    protected $fillable = [
        'user_id',
        'two_factor_auth',
        'login_alerts',
        'github_login',
        'google_login',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the security settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}