<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Interfaces\CrudInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements CrudInterface
{

    public function getAll(Request $request)
    {
        $length = $request->input('length') ? $request->input('length') : 15;
        $uid = $request->input('uid');

        $orders = DB::table('orders')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->select('orders.id', 'orders.uid', 'orders.product_price', 'orders.unit', 'orders.status', 'orders.created_at','products.title as product_title', 'products.image', 'users.name as u_name')
        ->when(!empty($uid), function ($query) use ($uid) {
            $query->where('orders.uid', 'like', "$uid%");
        })
            ->orderBy('orders.id', 'desc')
            ->paginate($length);
        return $orders;
    }

    public function create(Request $request)
    {
        $data = $request->only(['unit', 'product_price', 'product_id']);
        $data['uid'] = 'Or-'. time() . Str::random(1) . rand(10, 99);
        $data['user_id'] = Auth::id();
        $data['status'] = 'Processing';
        $order = Order::create($data);
        $order = Order::findOrfail($order->id);
        return $order;
    }

    public function findById($id)
    {
        $order = Order::findOrfail($id);
        return $order;
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrfail($id);
        if (!empty($request->input('status'))) {
            $data['status'] = $request->input('status');
        }
        if (!empty($request->input('unit'))) {
            $data['unit'] = $request->input('unit');
        }
        $order->fill($data)->save();
        return $order;
    }

    public function delete($id)
    {
        $order = Order::findOrfail($id);
        $order->delete();
        return $order;
    }
}
