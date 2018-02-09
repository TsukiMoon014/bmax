<?php

namespace bmax\src;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use bmax\src\middlewares\app\ExitLog;
use bmax\src\middlewares\app\EntryLog;
use bmax\src\middlewares\app\TrailingSlash;
use bmax\src\controllers\CheckerController;

require __DIR__.'/../../vendor/autoload.php';

// Global settings
require __DIR__.'/../app/config.php';
$app = new \Slim\App($config);

// Container registrations
$container = $app->getContainer();
require __DIR__.'/../app/dependencies.php';

// I/O logging with application middlewares
$app->add(new ExitLog($container->get('logger')));
$app->add(new EntryLog($container->get('logger')));

// Entry point to trail slashes
$app->add(new TrailingSlash());

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
