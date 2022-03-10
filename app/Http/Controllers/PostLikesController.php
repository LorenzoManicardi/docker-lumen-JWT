<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostLikesController extends Controller
{

    /**
     * @param string $post_id
     * @return void
     */
    public function likePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);

        if (auth()->user()->likedPosts()->where('post_id', $post_id)->exists()) {
            abort(405);
        } else {
            auth()->user()->likedPosts()->attach($post->id);
            return $post;
        }
    }


    /**
     * @param string $post_id
     * @return void
     */
    public function unLikePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);
        if (!auth()->user()->likedPosts()->where('post_id', $post_id)->exists()) {
            abort(405);
        } else {
            auth()->user()->likedPosts()->detach($post->id);
            return $post;
        }
    }

}
