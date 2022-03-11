<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return Post::orderBy('id', 'desc')->get();
    }

    /**
     * takes input a string "id" -> returns posts with eager loading of his user and comments
     * @param string $id
     * @return mixed
     */
    public function show(string $id)
    {
        return Post::findOrFail($id);
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
            'category' => 'required|string'
        ]);
        $newPost = new Post([
            "title" => $request->input('title'),
            "content" => $request->input('content')
        ]);
        $category = $request->input('category');
        $currCategory = Category::where('category_name', $category)->firstOrFail();
        $newPost->setUserId(Auth::user()->id);
        $newPost->save();
        $newPost->categories()->attach($currCategory->id);
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
            'category' => 'string'
        ]);
        $post = auth()->user()->posts()->findOrFail($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $category = $request->input('category');
        $currCategory = Category::where('category_name', $category)->firstOrFail();
        $post->save();
        $post->categories()->sync($currCategory);
        return $post;
    }


    /**
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

    /**
     * @return mixed
     */
    public function userPosts()
    {
        return auth()->user()->posts()->orderBy('id', 'desc')->get();
    }

    public function favoritePosts()
    {
        return auth()->user()->favorites()->get();
    }
}
