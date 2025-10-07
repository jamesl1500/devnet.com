<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts_media extends Model
{
    // Soft Deletes
    use SoftDeletes;

    // Table
    protected $table = 'posts_media';

    protected $fillable = [
        'post_id',
        'file_id',
        'caption',
        'type',
        'order',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the post that owns the media.
     */
    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id');
    }

    /**
     * Get the file associated with the media.
     */
    public function file()
    {
        return $this->belongsTo(Files::class, 'file_id');
    }
}