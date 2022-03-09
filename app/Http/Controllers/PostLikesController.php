<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;

class PostLikesController extends Controller
{

    /**
     * @param string $post_id
     * @return Exception|JsonResponse|string[]
     */
    public function likePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);
        try {
            auth()->user()->likedPosts()->attach($post->id);
            return ['status' => 'success', 'message' => 'post liked successfully!'];
        } catch (Exception $e) {
            return ['status' => 'something went wrong...', 'message' => 'you already liked this post!'];
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
            return ['status' => 'something went wrong', 'message' => 'you did not like this post!'];
        }
    }

}
