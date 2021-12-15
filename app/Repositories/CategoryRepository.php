<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Interfaces\CrudInterface;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CrudInterface
{

    public function getAll(Request $request)
    {
        $length = $request->input('length');
        $cTitle = $request->input('title');

        $categories = DB::table('categories')
                    ->select('categories.id', 'categories.title', 'categories.is_active', 'categories.created_at')
                    ->when(!empty($cTitle), function($query) use($cTitle) {
                        $query->where('categories.title', 'like', "%$cTitle%");
                    })
                    ->orderBy('categories.id', 'desc')
                    ->get();
        if(!empty($length)) {
            // can get pagination data from collection using "spatie/laravel-collection-macros" package
            return $categories->paginate($length);
        }
        return $categories;

        // Eloquent way but query builder shines
        // $categories = Category::select('id', 'title', 'is_active', 'created_at')
        // ->orderby('id', 'desc')
        // ->when(!empty($cTitle), function($query) use($cTitle) {
        //     $query->where('title', 'like', "%$cTitle%");
        // })
        // ->paginate($length);
    }

    public function create(Request $request)
    {
        $data = $request->only(['title', 'slug', 'description', 'is_active']);
        $data['slug'] = Str::slug($request->input('title'), '-');
        $category = Category::create($data);
        $category = Category::findOrfail($category->id);
        return $category;
    }

    public function findById($id)
    {
        $category = Category::findOrfail($id);
        return $category;
    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {
        $category = Category::findOrfail($id);
        $category->delete();
        return $category;
    }
}
