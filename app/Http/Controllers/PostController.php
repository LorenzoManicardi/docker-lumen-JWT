<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController extends Controller
{
    /**
     * @return Builder[]|Collection
     */
    public function index()
    {
        return Post::with(['user', 'comments'])->get();
    }

    /**
     * takes input a string "id" -> returns posts with eager loading of his user and comments
     * @param string $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function show(string $id)
    {
        return Post::with('user', 'comments')->findOrFail($id);
    }

    /**
     * takes input a request, creates and returns a Post object
     * @param Request $request
     * @return Post
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        /** @var Post $n */
        $n = new Post([
            "title" => $request->input('title'),
            "content" => $request->input('content')
        ]);
        $n->setUserId(Auth::user()->id);
        $n->save();
        return $n;
    }

    /**
     * takes input a request and an id, updates the Post if existing and returns it
     * @param Request $request
     * @param string $id
     * @return mixed
     * @throws ValidationException
     */
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

    /**
     * takes input an id, deletes the relative Post
     * @param string $id
     * @return array|string[]
     */
    public function destroy(string $id)
    {
        /** @var Post $p */
        $p = auth()->user()->posts()->findOrFail($id);
        $p->comments()->delete();
        try {
            $p->delete();
            return ["status" => "success", "message" => "deleted successfully!"];
        } catch (Throwable $e) {
            return ["status" => "error", "message" => $e];
        }
    }
}
