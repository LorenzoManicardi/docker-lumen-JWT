<?php

namespace App\Models;

use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string content
 * @method static findOrFail(string $post_id)
 * @method static WhereUserId()
 * @method static SearchTitle()
 * @method static orderBy($p1, $p2)
 */
class Post extends Model
{
    use UserTrait;

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'pivot',
        'category_id'
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
    public function setUserId($value): void
    {
        $this->attributes['user_id'] = $value;
    }


    /**
     * @return BelongsToMany
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps()->as('like info:');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereUserId(Builder $query): Builder
    {
        return $query->where('user_id', auth()->user()->id);
    }

    /**
     * @param Builder $query
     * @param string $toSearch
     * @return Builder
     */
    public function scopeSearchTitle(Builder $query, string $toSearch): Builder
    {
        return $query->where('title', 'like', '%' . $toSearch . '%');
    }

}
