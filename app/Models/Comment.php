<?php

namespace App\Models;

use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string content
 */

class Comment extends Model
{
    use UserTrait;

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'comment_likes')->withTimestamps();
    }

    protected $hidden = [
        'user_id',
        'post_id',
        'created_at',
        'updated_at',
        'pivot',
    ];

    protected $withCount = [
        'likes'
    ];

    protected $with = [
        'likes:id,name',
        'user'
    ];
}
