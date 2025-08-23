<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profiles extends Model
{
    // Soft deletes
    use SoftDeletes;

    // Table
    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'years_experience',
        'seniority',
        'current_job_title',
        'looking_for_work',
        'languages',
        'skills',
        'framework',
        'availability',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
