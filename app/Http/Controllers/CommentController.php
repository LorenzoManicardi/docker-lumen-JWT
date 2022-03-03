<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $post_id)
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
    public function update(Request $request, $post_id, $id)
    {
        if (auth()->user()->subscription == 'premium') {
            $this->validate($request, [
                "content" => 'string|required'
            ]);
            try {
                $n = auth()->user()->comments->where('post_id', $post_id)->where('id', $id)->first();
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

    public function destroy($post_id, $id)
    {
        if (auth()->user()->subscription == 'premium') {
            try {
                $n = auth()->user()->comments->where('post_id', $post_id)->where('id', $id)->first();
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
