<?php

namespace App\Http\Controllers\Guest;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Requests\Order\OrderStoreRequest;

class OrderController extends Controller
{
    /**
     * Заказы пользователя
     *
     * Get user orders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $active = auth()->user()->orders()->active()->paginate();
        $completed = auth()->user()->orders()->completed()->paginate();

        return response()->json([
            'active' => new OrderCollection($active),
            'completed'  => new OrderCollection($completed)
        ], 200);
    }

    /**
     * Добавление заказа
     *
     * Store a newly created orders in storage.
     *
     * @param OrderStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_status_code' => Order::STATUS_CREATED
        ]);

        return response()->json([
            'status' => 'success',
            'message' => __('orders.response.messages.created')
        ], 200);
    }
}
