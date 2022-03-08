<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;

class PostLikesController extends Controller
{

    /**
     * @param string $post_id
     * @return Exception|string[]
     */
    public function likePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);
        try {
            auth()->user()->likedPosts()->attach($post->id);
            return ['status' => 'success', 'message' => 'post liked successfully!'];
        } catch (Exception $e) {
            return $e;
        }
    }


    /**
     * @param string $post_id
     * @return Exception|string[]
     */
    public function unLikePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);
        try {
            auth()->user()->likedPosts()->detach($post->id);
            return ['status' => 'success', 'message' => 'post unliked successfully!'];
        } catch (Exception $e) {
            return $e;
        }
    }



}
