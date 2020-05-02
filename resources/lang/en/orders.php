<?php

/*
|--------------------------------------------------------------------------
| Языковые ресурсы заказов
|--------------------------------------------------------------------------
*/

return [
    'response' => [
        'messages' => [
            'created' => 'Order has been successfully created!',
            'updated' => 'Сведения о заказе успешно обновлены!',
            'deleted' => 'Заказ успешно удален!'
        ]
    ],

    'statuses' => [
        'created'   => 'created',
        'canceled'  => 'canceled',
        'payed'     => 'payed',
        'delivery'  => 'delivery',
        'completed' => 'completed'
    ],

    'export'   => [
        'headings' => [
            'id'         => '#',
            'customer'   => 'Customer',
            'status'     => 'Status',
            'price'      => 'Payment amount',
            'created_at' => 'Checkout date'
        ]
    ],

    'validation' => [
        'attributes'   => [
            'products' => 'Products',
            'customer' => 'Customer',
            'order_status_code' => 'Status'
        ],

        'messages' => [
            'invalid_format'  => 'Invalid format',
            'products_unique' => 'Product should not duplicates',
            'invalid_status'  => 'Wrong status'
        ]
    ]
];