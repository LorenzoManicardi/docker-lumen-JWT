<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string content
 * @method static findOrFail(string $post_id)
 */
class Post extends Model
{
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'pivot',
    ];

    protected $withCount = [
        'likes'
    ];

    protected $with = [
        'user',
        'comments',
        'likes'
    ];

    protected $fillable = [
        'title',
        'content'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param $value
     * @return void
     */
    public function setUserId($value)
    {
        $this->attributes['user_id'] = $value;
    }


    /**
     * @return BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps()->as('like info:');
    }

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
