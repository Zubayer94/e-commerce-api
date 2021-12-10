<?php

namespace App\Repositories;


use Illuminate\Http\Request;
use App\Interfaces\CrudInterface;
use App\Models\Product;

class ProductRepository implements CrudInterface
{

    public function getAll(Request $request)
    {
        $length = $request->input('length');
        $product = Product::select('title', 'slug', 'price', 'image', 'qty', 'created_at')
            ->orderby('id', 'desc')
            ->paginate($length);
        return $product;
    }

    public function create(Request $request)
    {
        $data = $request->only(['title', 'slug', 'price', 'image', 'description', 'qty']);
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

    }
}
