<?php

/*
|--------------------------------------------------------------------------
| Языковые ресурсы товаров
|--------------------------------------------------------------------------
*/

return [
    'response' => [
        'messages' => [
            'created' => 'Товар успешно создан!',
            'updated' => 'Сведения о товаре успешно обновлены!',
            'deleted' => 'Товар успешно удален!'
        ]
    ],

    'validation' => [
        'attributes' => [
            'title' => 'Наименование',
            'price' => 'Цена',
            'category_id' => 'Категория',
            'image' => 'Изображение'
        ]
    ]
];