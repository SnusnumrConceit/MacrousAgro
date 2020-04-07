<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderRepo
{
    /**
     * List of orders in storage
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $orders = Order::query();

        $orders->when($request->keyword, function ($q, $keyword) {
            return $q->where('id', $keyword);
        });

        $orders->when($request->status, function ($q, $status) {
           return $q->where('order_status_code', $status);
        });

        $orders->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . [' 23:59:59']]);
        });

        $orders = Order::with('customer', 'invoice')->latest()->paginate();

        return $orders;
    }

    /**
     * Store a newly created order in storage.
     *
     * @param array $orderData
     */
    public function store(array $orderData) : void
    {
        $amountPrice = $this->countOrderPrice($orderData['products']);

        $order = Order::create([
            'user_id' => 36, // TODO убрать хардкод и добавить auth()->id()
            'order_status_code' => Order::STATUS_CREATED
        ]);

        // TODO нарушение SPR - вынести в OrderService
        $products = array_map(function ($product) use ($order) {
            return [
                'product_id' => $product['id'],
                'order_id' => $order->id,
                'order_item_status_code' => $order->order_status_code
            ];
        }, $orderData['products']);

        $order->positions()->createMany($products);

        $order->invoice()->create([
            'payment_amount' => $amountPrice,
            'invoice_status_code' => 1
        ]);
    }

    /**
     * Get total price of order
     *
     * @param array $products
     * @return int
     */
    private function countOrderPrice(array $products) // TODO нарушение SPR - вынести в OrderService
    {
        $summa = 0;

        foreach ($products as $product) {
            $summa += $product['price'];
        }

        return $summa;
    }

    /**
     * Update the order in storage.
     *
     * @param array $orderData
     * @param Order $order
     */
    public function update(array $orderData, Order $order)
    {
        $order->update($orderData);

        $order->positions()->update(['order_item_status_code' => $order['order_status_code']]);

//        $order->positions->sync($orderData['products']);
    }

    /**
     * Remove the order from storage.
     *
     * @param Order $order
     */
    public function destroy(Order $order)
    {
        $order->delete();

        $order->positions->detach();
    }
}