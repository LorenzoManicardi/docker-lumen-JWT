<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;

class CommentLikesController extends Controller
{
    /**
     * @param string $comment_id
     * @return Exception|string[]
     */
    public function likeComment(string $comment_id)
    {
        $comment = Post::findOrFail($comment_id);
        try {
            auth()->user()->likedComments()->attach($comment->id);
            return ['status' => 'success', 'message' => 'comment liked successfully!'];
        } catch (Exception $e) {
            return $e;
        }
    }


    /**
     * @param string $comment_id
     * @return Exception|string[]
     */
    public function unLikeComment(string $comment_id)
    {
        $comment = Post::findOrFail($comment_id);
        try {
            auth()->user()->likedComments()->detach($comment->id);
            return ['status' => 'success', 'message' => 'post unliked successfully!'];
        } catch (Exception $e) {
            return $e;
        }
    }
}
