<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request)
    {
        $qTitle = $request->input('title');
        $orderBy = $request->input('orderBy');

        $products = DB::table('products')
        ->select('products.id', 'products.title', 'products.price', 'products.image', 'products.qty')
        ->when(!empty($qTitle), function ($query) use ($qTitle) {
            $query->where('products.title', 'like', "%$qTitle%");
        })
            ->orderBy('products.price', $orderBy)
            ->paginate(16);
        return response()->json(['response' => 'Success', 'products' => $products], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = $this->productRepository->findById($id);
            return response()->json(['response' => 'Success', 'product' => $product], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }
}
