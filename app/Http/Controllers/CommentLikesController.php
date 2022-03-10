<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentLikesController extends Controller
{


    /**
     * @param string $comment_id
     * @return Comment|void
     */
    public function likeComment(string $comment_id)
    {

        /** @var Comment $comment */
        $comment = Comment::findOrFail($comment_id);
        if (auth()->user()->likedComments()->where('comment_id', $comment_id)->exists()) {
            abort(405);
        } else {
            auth()->user()->likedComments()->attach($comment->id);
            return $comment;
        }
    }

    /**
     * @param string $comment_id
     * @return Comment|void
     */
    public function unLikeComment(string $comment_id)
    {
        /** @var Comment $comment */
        $comment = Comment::findOrFail($comment_id);
        if (auth()->user()->likedComments()->where('comment_id', $comment_id)->exists()) {
            auth()->user()->likedComments()->detach($comment->id);
            return $comment;
        } else {
            abort(405);
        }
    }
}
