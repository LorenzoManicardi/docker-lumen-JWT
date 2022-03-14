<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    /**
     * @return Category[]|Collection
     */
    public function index()
    {
        return Category::all();
    }


    /**
     * @param $cat_name
     * @return Category
     */
    public function show($cat_name): Category
    {
        /** @var Category $category */
        $category =  Category::where('category_name', $cat_name)->firstOrFail();
        return $category;
    }
}
