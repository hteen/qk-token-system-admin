<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'manager',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'manager',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'manager',
        ],
    ],
    'providers' => [
        'manager' => [
            'driver' => 'diy',
            'model' => \App\Model\Manage\Manager::class,
        ],
    ],

];
