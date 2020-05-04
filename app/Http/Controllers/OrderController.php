<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Resources\Order\OrderDetail;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderUpdateRequest;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
//        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Список заказов
     *
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::query();

        $orders->when($request->keyword, function ($q, $keyword) {
            return $q->where('id', 'LIKE', '%' . $keyword . '%');
        });

        $orders->when($request->status, function ($q, $status) {
            return $q->where('order_status_code', $status);
        });

        $orders->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . ' 23:59:59']);
        });

        $orders = $orders->with('customer', 'invoice')->latest()->paginate();

        return response()->json([
            'orders' => new OrderCollection($orders)
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
            'user_id' => auth()->id,
            'order_status_code' => Order::STATUS_CREATED
        ]);

        return response()->json([
            'status' => 'success',
            'message' => __('orders.response.messages.created')
        ], 200);
    }

    /**
     * Информация о заказе
     *
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
     * Форма редактирования заказа
     *
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
     * Обновление заказа
     *
     * Update the order in storage.
     *
     * @param OrderUpdateRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => __('orders.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление заказа
     *
     * Remove the order from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'status'  => 'success',
            'message' => __('orders.response.messages.deleted')
        ], 200);
    }

    /**
     * Экспорт заказов
     *
     * Export listing of the orders
     *
     * @param Request $request
     * @return \App\Exports\OrdersExport
     */
    public function export(Request $request)
    {
        return $this->orderService->export();
    }
}
