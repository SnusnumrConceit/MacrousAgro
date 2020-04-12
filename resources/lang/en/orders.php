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

    'validation' => [
        'attributes' => [
            'products' => 'Products',
            'customer' => 'Customer',
            'order_status_code' => 'Status'
        ],

        'messages' => [
            'invalid_format' => 'Invalid format',
            'products_unique' => 'Product should not duplicates',
            'invalid_status' => 'Wrong status'
        ]
    ]
];