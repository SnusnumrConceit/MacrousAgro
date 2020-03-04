<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderRepo
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Order::paginate(15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        $order = Order::create($request->validated());

        $order->positions->sync($request->products);

        return response()->json([
            'message' => 'Заказ успешно добавлен'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order = $order->update($request->validated());

        $order->positions->sync($request->products);

        return response()->json([
            'message' => 'Заказ успешно отредактирован'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        $order->positions->detach();

        return response()->json([
            'message' => 'Заказ успешно удалён'
        ], 200);
    }

    public function search(Request $request)
    {
        $orders = Order::query();

        $orders->when($request->keyword, function ($q, $keyword) {
//           return $q->products()->;
        });

        $orders->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00', $created_at . ' 23:59']);
        });

        $orders->when($request->status_code, function ($q, $status_code) {
            return $q->status()->where('id', $status_code);
        });

        return $orders->paginate(15);
    }
}