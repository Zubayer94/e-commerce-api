<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
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
    public function index(Request $request)
    {
        $product = $this->productRepository->getAll($request);
        return response()->json(['response' => 'Success', 'products' => $product], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:250',
            'price' => 'required',
            'qty' => 'required|numeric',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $product = $this->productRepository->create($request);
            return response()->json(['response' => 'success', 'product' => $product], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            $product = $this->productRepository->findById($product->id);
            return response()->json(['response' => 'Success', 'product' => $product], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'title' => 'required|string|max:250',
            'price' => 'required',
            'qty' => 'required|numeric',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'nullable|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $prod = $this->productRepository->update($request, $product->id);
            return response()->json(['response' => 'Product Updated successfully', 'product' => $prod], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product = $this->productRepository->delete($product->id);
            return response()->json(['response' => 'Success', 'product' => $product], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }
}
