<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'groups';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'visibility',
        'owner_id',
        'icon_id',
        'cover_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the users for the group.
     */
    public function users()
    {
        return $this->hasMany(Group_users::class, 'group_id');
    }

    /**
     * Get the icon for the group.
     */
    public function icon()
    {
        return $this->belongsTo(Files::class, 'icon_id');
    }

    /**
     * Get the cover for the group.
     */
    public function cover()
    {
        return $this->belongsTo(Files::class, 'cover_id');
    }
}
