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
        return Post::with(['user', 'comments.user:id,name,picture,email'])
            ->orderBy('id', 'desc')->get();
    }

    /**
     * takes input a string "id" -> returns posts with eager loading of his user and comments
     * @param string $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function show(string $id)
    {
        return Post::with('user', 'comments.user:id,name,picture,email')->findOrFail($id);
    }

    /**
     * takes input a request, creates and returns a Post object
     * @param Request $request
     * @return Post
     * @throws ValidationException
     */
    public function store(Request $request): Post
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $newPost = new Post([
            "title" => $request->input('title'),
            "content" => $request->input('content')
        ]);
        $newPost->setUserId(Auth::user()->id);
        $newPost->save();
        return $newPost;
    }

    /**
     * takes input a request and an id, updates the Post if existing and returns it
     * @param Request $request
     * @param string $id
     * @return Post
     * @throws ValidationException
     */
    public function update(Request $request, string $id): Post
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $post = auth()->user()->posts()->findOrFail($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
        return $post;
    }


    /**
     * per Steve: qua meglio che la funzione ritorni qualche altro tipo di dato o ha senso così?
     * @param string $id
     * @return array|string[]
     */
    public function destroy(string $id): array
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

    public function userPosts()
    {
        return auth()->user()->posts()->with('comments.user:id,name,picture,email')
            ->orderBy('id', 'desc')->get();
    }
}
