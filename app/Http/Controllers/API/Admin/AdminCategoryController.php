<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;

class AdminCategoryController extends Controller
{
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->categoryRepository->getAll($request);
        return response()->json(['response' => 'Success', 'categories' => $categories], 200);
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
            'is_active' => 'required|numeric',
            'description' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $category = $this->categoryRepository->create($request);
            return response()->json(['response' => 'success', 'category' => $category], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        try {
            $cat = $this->categoryRepository->findById($category->id);
            return response()->json(['response' => 'Success', 'category' => $cat], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'title' => 'required|string|max:250',
            'is_active' => 'required|numeric',
            'description' => 'nullable|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $cat = $this->categoryRepository->update($request, $category->id);
            return response()->json(['response' => 'Category Updated successfully', 'category' => $cat], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $cat = $this->categoryRepository->delete($category->id);
            return response()->json(['response' => 'Success', 'category' => $cat], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }
}
