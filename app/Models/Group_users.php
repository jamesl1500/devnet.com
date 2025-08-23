<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group_users extends Model
{
    // Soft deletes
    use SoftDeletes;

    // Table
    protected $table = 'group_users';

    protected $fillable = [
        'user_id',
        'group_id',
        'role',
        'joined_at'
    ];

    /**
     * Get the user that owns the group_user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the group that owns the group_user.
     */
    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }
}
