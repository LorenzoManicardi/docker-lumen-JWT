<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostLikesController extends Controller
{

    /**
     * @param string $post_id
     * @return Exception|Post
     */
    public function likePost(string $post_id)
    {
        $post = Post::findOrFail($post_id);
        try {
            auth()->user()->likedPosts()->attach($post->id);
            return $post;
        } catch (Exception $e) {
            return $e;
        }
    }



}
