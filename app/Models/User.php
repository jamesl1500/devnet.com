<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'role',
        'avatar_id',
        'cover_id',
        'headline',
        'bio',
        'location',
        'website',
        'settings',
        'provider_id',
        'token',
        'refresh_token',
        'expires_in'
    ];

    /**
     * Get the users avatar.
     */
    public function avatar()
    {
        return $this->belongsTo(Files::class, 'avatar_id');
    }

    /**
     * Get the users cover.
     */
    public function cover()
    {
        return $this->belongsTo(Files::class, 'cover_id');
    }

    /**
     * Profile
     */
    public function profile()
    {
        return $this->hasOne(Profiles::class);
    }

    // Follows
    public function followers()
    {
        return $this->morphMany(Follows::class, 'followable');
    }

    public function following()
    {
        return $this->hasMany(Follows::class, 'follower_id');
    }

    /**
     * Notification Settings
     */
    public function notificationSettings()
    {
        return $this->hasOne(Notifications_settings::class, 'user_id');
    }

    /**
     * Security Settings
     */
    public function securitySettings()
    {
        return $this->hasOne(Security_settings::class, 'user_id');
    }

    /**
     * Privacy Settings
     */
    public function privacySettings()
    {
        return $this->hasOne(Privacy_settings::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
