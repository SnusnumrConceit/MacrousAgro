<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Repositories\OrderRepo;
use App\Http\Resources\Order\OrderDetail;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderUpdateRequest;

class OrderController extends Controller
{
    private $order, $orderService;

    public function __construct(OrderRepo $order, OrderService $orderService)
    {
        $this->order = $order;
        $this->orderService = $orderService;
//        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->order->index($request);

        return response()->json([
            'orders' => new OrderCollection($orders)
        ], 200);
    }

    /**
     * Store a newly created orders in storage.
     *
     * @param OrderStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(OrderStoreRequest $request)
    {
        $this->order->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('orders.response.messages.created')
        ], 200);
    }

    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json([
            'order' => new OrderDetail($order)
        ], 200);
    }

    /**
     * Show the form for editing the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return response()->json([
            'order' => $order
        ], 200);
    }

    /**
     * Update the order in storage.
     *
     * @param OrderUpdateRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->order->update($request->validated(), $order);

        return response()->json([
            'status'  => 'success',
            'message' => __('orders.response.messages.updated')
        ], 200);
    }

    /**
     * Remove the order from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Order $order)
    {
        $this->order->destroy($order);

        return response()->json([
            'status'  => 'success',
            'message' => __('orders.response.messages.deleted')
        ], 200);
    }

    /**
     * Поиск по заказам
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->order->search($request);
    }

    /**
     * Экспорт заказов
     *
     * @param Request $request
     * @return \App\Exports\OrdersExport
     */
    public function export(Request $request)
    {
        return $this->orderService->export();
    }
}
