<?php

return [
    'response' => [
        'messages' => [
            'created' => 'Role has been successfully created!',
            'updated' => 'Role has been successfully updated!',
            'deleted' => 'Role has been successfully deleted!!'
        ]
    ],

    'validation' => [
        'attributes' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description'
        ],

        'messages' => [
            'permissions.array' => 'Invalid format',
            'permissions.min' => 'Role should contains one permission at least',
            'permissions.*.required' => 'Role should contains one permission at least',
            'permissions.*.exists' => 'Permission does not exists'
        ]
    ]
];