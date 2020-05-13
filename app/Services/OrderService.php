<?php

namespace App\Services;


use App\Models\Order;
use App\Models\Product;
use App\Exports\OrdersExport;

class OrderService
{
    /**
     * Экспорт заказов
     *
     * @return OrdersExport
     */
    public function export()
    {
        return new OrdersExport;
    }

    /**
     * Получить полную стоимость заказа
     *
     * @param array $products
     * @return int
     */
    public function countOrderPrice(array $products)
    {
        return Product::whereIn('id', $products)->sum('price');
    }

    /**
     * Подготовить товары для сохранения в позициях заказа
     *
     * @param array $products
     * @param Order $order
     * @return array
     */
    public function prepareOrderPositions(array $products, Order $order)
    {
        return array_map(function ($product_id) use ($order) {
            return [
                'product_id' => $product_id,
                'order_id' => $order->id,
                'order_item_status_code' => $order->order_status_code
            ];
        }, $products);
    }
}