<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        return Post::with(['user', 'comments'])->get();
    }

    /**
     * takes input a string "id" -> returns posts with eager loading of his user and comments
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(string $id)
    {
        return Post::with('user', 'comments')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $n = new Post([
            "user_id" => auth()->user()->id,
            "title" => $request->title,
            "content" => $request->content
        ]);
        $n->save();
        return $n;
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        $p = auth()->user()->posts()->findOrFail($id);

        $p->title = $request->input('title');
        $p->content = $request->input('content');
        $p->save();
        return $p;
    }

    public function destroy(string $id)
    {

        $p = auth()->user()->posts()->findOrFail($id);
            if ($p->comments) {
                foreach ($p->comments as $comment ) {
                    $comment->delete();
                }
            }
            try {
                $p->delete();
                return ["status" => "success", "message" => "deleted successfully!"];
            } catch (\Error $e) {
                return ["status" => "error", "message" => $e];
            }
    }
}
