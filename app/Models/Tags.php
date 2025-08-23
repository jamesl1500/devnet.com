<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tags extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Icon id
     */
    public function icon()
    {
        return $this->belongsTo(Files::class, 'icon_id');
    }
}
