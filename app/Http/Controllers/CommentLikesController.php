<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;

class CommentLikesController extends Controller
{
    /**
     * @param string $comment_id
     * @return Exception|string[]
     */
    public function likeComment(string $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        try {
            auth()->user()->likedComments()->attach($comment->id);
            return ['status' => 'success', 'message' => 'comment liked successfully!'];
        } catch (Exception $e) {
            return ['status' => 'something went wrong...', 'message' => 'you already liked this comment!'];
        }
    }


    /**
     * @param string $comment_id
     * @return Exception|string[]
     */
    public function unLikeComment(string $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        try {
            auth()->user()->likedComments()->detach($comment->id);
            return ['status' => 'success', 'message' => 'comment unliked successfully!'];
        } catch (Exception $e) {
            return ['status' => 'something went wrong...', 'message' => 'you did not like this comment!'];
        }
    }
}
