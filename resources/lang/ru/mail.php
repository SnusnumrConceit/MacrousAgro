<?php

return [
    'greetings' => [
        'welcome' => 'Добро пожаловать в ' . config('app.name')
    ],

    'subjects' => [
        'welcome' => 'Вы успешно зарегистрированы',
        'new_user' => 'Регистрация нового пользователя',

        'new_order' => 'Новый заказ',
        'order_created' => 'Вы совершили заказ'
    ],

    'body' => [
        'welcome' => ':name, наша агробиржа :app_name к Вашим услугам!',
        'new_user' => 'Зарегистрирован новый пользователь :full_name :email',

        'new_order' => 'Пользователь :full_name :email совершил заказ :order_id',
        'order_created' => ':name, Вы совершили заказ.'
    ],

    'buttons' => [
        'landing' => 'На главную',
        'details' => 'Подробнее'
    ],

    'footer' => 'С уважением,'
];