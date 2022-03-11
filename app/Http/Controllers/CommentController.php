<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{

    use UserTrait;

    /**
     * @param Request $request
     * @param string $post_id
     * @return Comment
     * @throws ValidationException
     */
    public function store(Request $request, string $post_id): Comment
    {
        $this->validate($request, [
            "content" => 'string|required'
        ]);
        /** @var Post $post */
        $post = Post::findOrFail($post_id);
        $newComment = new Comment();
        $newComment->user_id = $this->getUserId();
        $newComment->post_id = $post->id;
        $newComment->content = $request->input('content');
        $newComment->save();
        return $newComment;
    }

    /**
     * @param Request $request
     * @param string $post_id
     * @param string $id
     * @return Comment
     * @throws ValidationException
     */
    public function update(Request $request, string $post_id, string $id): Comment
    {
        $this->validate($request, [
            "content" => 'string|required'
        ]);
        if (Auth::user()->isPremium()) {
            /** @var Comment $comment */
            $comment = Auth::user()->comments()->where('post_id', $post_id)->findOrFail($id);
            $comment->content = $request->input('content');
            $comment->save();
        } else {
            abort(403);
        }
        return $comment;
    }

    /**
     * @param string $post_id
     * @param string $id
     * @return string[]
     */
    public function destroy(string $post_id, string $id): array
    {
        if (Auth::user()->subscription == 'premium') {
            /** @var Comment $comment */
            $comment = Auth::user()->comments()->where('post_id', $post_id)->findOrFail($id);
            $comment->delete();
        } else {
            abort(403);
        }
        return ["status" => "success", "message" => "comment deleted successfully!"];
    }
}
