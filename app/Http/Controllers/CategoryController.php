<?php

namespace App\Http\Controllers;


use App\Models\Post;

class CategoryController extends Controller
{
    public function index()
    {
        /** @var Post $post */
        $post = new Post([
            'title' => 'primo titolo',
            'content' => 'testo primo post'
        ]);
        $post->saveWithUserId();
        return $post;
    }
}
