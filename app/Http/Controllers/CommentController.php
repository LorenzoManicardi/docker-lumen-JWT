<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param string $post_id
     * @return array|string[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, string $post_id)
    {
        $this->validate($request, [
            "content" => 'string|required'
        ]);
        try {
            $n = new Comment();
            $n->user_id = auth()->user()->id;
            $n->post_id = $post_id;
            $n->content = $request->content;
            $n->save();
            return ["status" => "success", "message" => "comment created successfully!"];
        } catch(\Error $e) {
            return ["status" => "error", "message" => $e];
        }
    }

    /**
     * @param Request $request
     * @param string $post_id
     * @param string $id
     * @return array|string[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $post_id, string $id)
    {
        if (auth()->user()->subscription == 'premium') {
            $this->validate($request, [
                "content" => 'string|required'
            ]);
            try {
                $n = auth()->user()->comments()->where('post_id', $post_id)->findOrFail($id);
                $n->content = $request->content;
                $n->save();
                return ["status" => "success", "message" => "comment updated successfully!"];
            } catch(\Error $e) {
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
    public function destroy(string $post_id, string $id)
    {
        if (auth()->user()->subscription == 'premium') {
            try {
                $n = auth()->user()->comments()->where('post_id', $post_id)->findOrFail($id);
                $n->delete();
                return ["status" => "success", "message" => "comment deleted successfully!"];
            } catch(\Error $e) {
                return ["status" => "error", "message" => $e];
            }
        } else {
            return ["status" => "error", "message" => "you must be a premium user for this feature!"];
        }
    }
}
