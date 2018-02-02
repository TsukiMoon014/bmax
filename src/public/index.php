<?php

namespace bmax\src;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use bmax\src\controllers\CheckerController;

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../app/config.php';
require __DIR__.'/../app/dependencies.php';


// Application Middleware
// Permanently redirect paths with a trailing slash to their non-trailing counterpart
$app->add(function (Request $request, Response $response, callable $next) {

    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        $uri = $uri->withPath(substr($path, 0, -1));

        // Actual routing
        if($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        }
        else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});

// Application Middleware
// Central Entry Point
// Log all i/o
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

    // Passing it to entry point controls
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

$app->get('/checker', CheckerController::class.':checker')
->setName('checker');

// App running
$app->run();
