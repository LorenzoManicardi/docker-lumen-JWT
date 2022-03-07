<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param string $post_id
     * @return array|string[]
     * @throws ValidationException
     */
    public function store(Request $request, string $post_id): array
    {
        $this->validate($request, [
            "content" => 'string|required'
        ]);
        /** @var Post $post */
        $post = Post::findOrFail($post_id);
        if ($post) {
            try {
                $n = new Comment();
                $n->user_id = Auth::user()->id;
                $n->post_id = $post->id;
                $n->content = $request->input('content');
                $n->save();
                return ["status" => "success", "message" => "comment created successfully!"];
            } catch(Throwable $e) {
                return ["status" => "error", "message" => $e];
            }
        } else {
            return ["status" => "error", "message" => "post not found!"];
        }
    }

    /**
     * @param Request $request
     * @param string $post_id
     * @param string $id
     * @return array|string[]
     * @throws ValidationException
     */
    public function update(Request $request, string $post_id, string $id): array
    {
        if (Auth::user()->subscription == 'premium') {
            $this->validate($request, [
                "content" => 'string|required'
            ]);
            $n = Auth::user()->comments()->where('post_id', $post_id)->findOrFail($id);
            try {
                $n->content = $request->input('content');
                $n->save();
                return ["status" => "success", "message" => "comment updated successfully!"];
            } catch(Throwable $e) {
                return ["status" => "error", "message" => $e];
            }
        } else {
            return ["status" => "error", "message" => "you must be a premium user for this feature!"];
        }

    }

    /**
     * @param string $post_id
     * @param string $id
     * @return array|string[]
     */
    public function destroy(string $post_id, string $id): array
    {
        if (Auth::user()->subscription == 'premium') {
            try {
                $n = Auth::user()->comments()->where('post_id', $post_id)->findOrFail($id);
                $n->delete();
                return ["status" => "success", "message" => "comment deleted successfully!"];
            } catch(Throwable $e) {
                return ["status" => "error", "message" => $e];
            }
        } else {
            return ["status" => "error", "message" => "you must be a premium user for this feature!"];
        }
    }
}
