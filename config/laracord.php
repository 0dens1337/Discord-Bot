<?php

return [
    'messages' => [
        'welcome_channel_id' => env('WELCOME_CHANNEL_ID', 'meow'),
        'role_channel_id' => env('ROLE_CHANNEL_ID', 'meow'),
        'log_channel_id' => env('LOG_CHANNEL_ID', 'meow'),
    ],

    'server' => [
        'server_id' => env('SERVER_ID', 'meow'),
    ],

    'role' => [
        'admin' => env('ADMIN_ROLE_ID', 'meow'),
        'donater' => env('DONATER_ROLE_ID', 'meow'),
        'easter_egg' => env('EASTER_EGG_ROLE_ID', 'meow'),
    ]
];
