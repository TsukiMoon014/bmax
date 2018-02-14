<?php

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,

        'db' => [
            'host'   => 'localhost',
            'user'   => 'root',
            'pass'   => 'trois2CINQ',
            'dbname' => 'bmax'
        ],

        'logger' => [
            'name' => 'io_watcher',
            'level' => \Monolog\Logger::DEBUG,
            'path' => '../logs/app.log'
        ],

        'template' => [
            'path' => '../templates/'
        ]
    ]
];
