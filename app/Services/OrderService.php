<?php

namespace App\Services;


use App\Models\Order;
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
        $summa = 0;

        foreach ($products as $product) {
            $summa += $product['price'];
        }

        return $summa;
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
        return array_map(function ($product) use ($order) {
            return [
                'product_id' => $product['id'],
                'order_id' => $order->id,
                'order_item_status_code' => $order->order_status_code
            ];
        }, $products);
    }
}