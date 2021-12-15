<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends Controller
{
    public $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order = $this->orderRepository->getAll($request);
        return response()->json(['response' => 'Success', 'orders' => $order], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        try {
            $order = $this->orderRepository->findById($order->id);
            return response()->json(['response' => 'Success', 'order' => $order], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $rules = [
            'status' => 'nullable|string|max:250',
            'unit' => 'required|numeric',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        try {
            $order = $this->orderRepository->delete($order->id);
            return response()->json(['response' => 'Success', 'order' => $order], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['response' => $ex->getMessage()], 404);
        }
    }
}
