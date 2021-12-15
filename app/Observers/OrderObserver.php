<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $orderHistory = new OrderHistory();
        $orderHistory->product_price = $order->product_price;
        $orderHistory->status = $order->status;
        $orderHistory->unit = $order->unit;
        $orderHistory->order_id = $order->id;
        $orderHistory->updated_by = Auth::id();
        $orderHistory->save();
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $orderHistory = new OrderHistory();
        $orderHistory->product_price = $order->product_price;
        $orderHistory->status = $order->status;
        $orderHistory->unit = $order->unit;
        $orderHistory->order_id = $order->id;
        $orderHistory->updated_by = Auth::id();
        $orderHistory->save();
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
