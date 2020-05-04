<?php

return [
    'greetings' => [
        'welcome' => 'Добро пожаловать в ' . config('app.name')
    ],

    'subjects' => [
        'welcome' => 'Вы успешно зарегистрированы',
        'new_user' => 'Регистрация нового пользователя'
    ],

    'body' => [
        'welcome' => ':name, наша агробиржа :app_name к Вашим услугам!',
        'new_user' => 'Зарегистрирован новый пользователь :full_name :email'
    ],

    'buttons' => [
        'landing' => 'На главную',
        'details' => 'Подробнее'
    ],

    'footer' => 'С уважением,'
];