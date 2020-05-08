<?php

return [
    'response' => [
        'messages' => [
            'created' => 'Роль успешно создана!',
            'updated' => 'Сведения о роли успешно обновлены!',
            'deleted' => 'Роль успешно удалена!'
        ]
    ],

    'validation' => [
        'attributes' => [
            'name' => 'Название',
            'slug' => 'Псевдоним',
            'description' => 'Описание'
        ],

        'messages' => [
            'permissions.array' => 'Неверный формат',
            'permissions.min' => 'Роль должна содержать хотя бы одно право',
            'permissions.*.required' => 'Роль должна содержать хотя бы одно право',
            'permissions.*.exists' => 'Право не найдено'
        ]
    ]
];