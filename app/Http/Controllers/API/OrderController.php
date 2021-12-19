<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = $request->input('length') ? $request->input('length') : 15;
        $uid = $request->input('uid');

        $orders = DB::table('orders')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->select('orders.id', 'orders.uid', 'orders.product_price', 'orders.unit', 'orders.status', 'orders.created_at', 'products.title as product_title', 'products.image')
        ->where('orders.user_id', Auth::id() )
        ->when(!empty($uid), function ($query) use ($uid) {
            $query->where('orders.uid', 'like', "$uid%");
        })
            ->orderBy('orders.id', 'desc')
            ->paginate($length);

        return response()->json(['response' => 'Success', 'orders' => $orders], 200);
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
            'unit' => 'required|numeric',
            'product_price' => 'required|numeric',
            'product_id' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $order = $this->orderRepository->create($request);
            return response()->json(['response' => 'success', 'order' => $order], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order = Order::findOrfail($id);
            $product = Product::select('qty')->findOrfail($order->product_id);
            return response()->json(['response' => 'Success', 'order' => $order, 'product_qty' => $product->qty], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $rules = [
            'unit' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 404);
        }

        try {
            $prod = $this->orderRepository->update($request, $order->id);
            return response()->json(['response' => 'Order Updated successfully', 'order' => $prod], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }
}
