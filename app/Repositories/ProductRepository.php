<?php

namespace App\Repositories;


use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Interfaces\CrudInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository implements CrudInterface
{

    public function getAll(Request $request)
    {
        $length = $request->input('length') ? $request->input('length') : 15;
        $qTitle = $request->input('title');

        $products = DB::table('products')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->select('products.id', 'products.title', 'products.slug', 'products.price', 'products.image', 'products.qty', 'categories.title as category_title', 'products.created_at')
                    ->when(!empty($qTitle), function($query) use($qTitle) {
                        $query->where('products.title', 'like', "%$qTitle%");
                    })
                    ->orderBy('products.id', 'desc')
                    ->paginate($length);
        return $products;

        // Eloquent way but query builder shines
        // $product = Product::select('title', 'slug', 'price', 'image', 'qty', 'created_at')
        // ->orderby('id', 'desc')
        // ->with('category')
        // ->paginate($length);
    }

    public function create(Request $request)
    {
        $data = $request->only(['title', 'slug', 'price', 'image', 'description', 'qty']);
        $data['slug'] = Str::slug($request->input('title'), '-');
        $product = Product::create($data);
        $product = Product::findOrfail($product->id);
        return $product;
    }

    public function findById($id)
    {
        $product = Product::findOrfail($id);
        return $product;
    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {
        $product = Product::findOrfail($id);
        $product->delete();
        return $product;
    }
}
