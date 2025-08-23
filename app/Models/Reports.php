<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reports extends Model
{
    // Soft deletes
    use SoftDeletes;

    // Table
    protected $table = 'reports';

    protected $fillable = [
        'reporter_id',
        'reason',
        'notes',
        'status',
        'reviewed_by',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the user that reviewed the report.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Morph
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
