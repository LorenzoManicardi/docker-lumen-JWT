<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    protected $with = [
        'posts'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
