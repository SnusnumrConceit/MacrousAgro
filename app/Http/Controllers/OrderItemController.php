<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Http\Requests\Order\Item\OrderItemUpdateRequest;

class OrderItemController extends Controller
{
    /**
     * Обновление статуса в позиции заказа
     *
     */
    public function update(OrderItemUpdateRequest $request, int $item_id)
    {
        $orderItem = OrderItem::findOrFail($item_id);

        $orderItem->update($request->validated());
    }
}
