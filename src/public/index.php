<?php

namespace bmax\src;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use bmax\src\controllers\CheckerController as toto;

require __DIR__.'/../../vendor/autoload.php';

var_dump(new toto\CheckerController());

/*

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
$app = new \Slim\App($config);
$container = $app->getContainer();

// Monolog service
$container['logger'] = function($container) {
    $logger = new \Monolog\Logger($container['settings']['logger']['name']);
    $file_handler = new \Monolog\Handler\StreamHandler($container['settings']['logger']['path']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// DB service
$container['db'] = function ($container) {
    $db = $container['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Template service
$container['view'] = function($container){
    return new \Slim\Views\PhpRenderer($container['settings']['template']['path']);
};

// Service Checker
/*$container['checkerController'] = function($container){
    return new CheckerController($container->db);
};

// Application middleware
// to log all i/o
$app->add(function (Request $request, Response $response, callable $next) {

    // Logging the raw call
    $uri = $request->getUri();
    $this->logger->addInfo('--------------NEW CALL--------------');
    $this->logger->addInfo('Scheme : '.$uri-> getScheme());
    $this->logger->addInfo('User : '.$uri-> getUserInfo());
    $this->logger->addInfo('Host : '.$uri-> getHost());
    $this->logger->addInfo('Port : '.$uri-> getPort());
    $this->logger->addInfo('BasePath : '.$uri-> getBasePath());
    $this->logger->addInfo('Path : '.$uri-> getPath());
    $this->logger->addInfo('Query : '.$uri-> getQuery());
    $this->logger->addInfo('Fragment : '.$uri-> getFragment());

    // Logging headers
    $headers = $request->getHeaders();
    foreach ($headers as $name => $values) {
        $this->logger->addInfo($name . ' : ' . implode(", ", $values));
    }


    // Logging the routing
    // Possible thanks to 'determineRouteBeforeAppMiddleware' => true
    $route = $request->getAttribute('route');

    // Return NotFound for non existent route
    if (empty($route)) {
        $this->logger->addInfo('--------------NO MATCH--------------');
        throw new NotFoundException($request, $response);
    }

    $this->logger->addInfo('--------------MATCH FOUND--------------');
    $this->logger->addInfo('Pattern : '.$route->getPattern());
    $this->logger->addInfo('Name : '.$route->getName());
    $this->logger->addInfo('Groups : ('.implode(") (",$route->getGroups()).')');
    $this->logger->addInfo('Methods : ('.implode(") (",$route->getMethods()).')');
    $this->logger->addInfo('Arguments : ('.implode(") (",$route->getArguments()).')');

    // Adding the session in read_only
    //$request = $request->withAttribute('session', $_SESSION);

    // Actual routing
    $response = $next($request, $response);

    // Logging exiting response
    $this->logger->addInfo('Exiting code : '.$response->getStatusCode());
    $this->logger->addInfo('--------------END OF CALL--------------');
    return $response;
});

// Actual routing
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response = $this->view->render($response, 'test.phtml', ['name' => $name, 'router' => $this->router]);

    return $response;
})->setName('hello');

$app->get('/check', CheckerController::class.':check')->setName('checker');

$app->get('/',function (Request $request, Response $response, array $args) {
    $a = new CheckerController();
});

// App running
$app->run();
*/