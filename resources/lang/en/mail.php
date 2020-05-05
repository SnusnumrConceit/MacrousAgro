<?php

return [
    'greetings' => [
        'welcome' => 'Welcome to ' . config('app.name')
    ],

    'subjects' => [
        'welcome' => 'You have been successfully registered',
        'new_user' => 'New user has been registered',

        'new_order' => 'New order',
        'order_created' => 'You make a order'
    ],

    'body' => [
        'welcome' => ':name, our agribusiness :app_name at Your service!',
        'new_user' => 'New user has been registered :full_name :email',

        'new_order' => 'Customer :full_name :email made the order :order_id',
        'order_created' => ':name, You successfully ordered.'
    ],

    'buttons' => [
        'landing' => 'Homepage',
        'details' => 'Details'
    ],

    'footer' => 'Best regards,'
];