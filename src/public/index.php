<?php

namespace bmax\src;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use bmax\src\controllers\CheckerController;

require __DIR__.'/../../vendor/autoload.php';

// Global settings
require __DIR__.'/../app/config.php';

// Container registrations
require __DIR__.'/../app/dependencies.php';

// I/O logging with application middlewares
require __DIR__.'/../middlewares/app/exitLog.php';
require __DIR__.'/../middlewares/app/entryLog.php';

// Entry point to trail slashes
require __DIR__.'/../middlewares/app/trailingSlash.php';

// Actual routing
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response = $this->view->render($response, 'test.phtml', ['name' => $name, 'router' => $this->router]);

    return $response;
})->setName('hello');

$app->get('/checker', CheckerController::class.':checker')
->setName('checker');

// App running
$app->run();
