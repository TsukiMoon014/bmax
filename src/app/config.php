<?php

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,

        'db' => [
            'host'   => 'localhost',
            'user'   => 'root',
            'pass'   => '',
            'dbname' => 'bmax'
        ],

        'logger' => [
            'name' => 'io_watcher',
            'level' => \Monolog\Logger::DEBUG,
            'path' => '../logs/app.log'
        ],

        'template' => [
            'path' => '../templates/'
        ],

        'utils' => [
            'the_forge_region_id' => 10000002
        ]
    ]
];
