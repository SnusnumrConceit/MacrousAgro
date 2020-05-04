<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderService;

class OrderObserver
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $products = request()->input('products');

        $amountPrice = $this->orderService->countOrderPrice($products);

        $products = $this->orderService->prepareOrderPositions($products, $order);

        $order->positions()->createMany($products);

        $order->invoice()->create([
            'payment_amount' => $amountPrice,
            'invoice_status_code' => 1
        ]);
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $order->positions()->update(['order_item_status_code' => $order['order_status_code']]);
    }

    /**
     * Handle the order "deleting" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleting(Order $order)
    {
        $order->positions->detach();
    }
}
