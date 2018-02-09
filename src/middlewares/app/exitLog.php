<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Application Middleware
// Central Log Exit Point
$app->add(function (Request $request, Response $response, callable $next){
    // Passing it to routing
    // as this middleware is handling things afterward it
    $response = $next($request, $response);

    // Logging exiting response
    $this->logger->addInfo('Exiting code : '.$response->getStatusCode());
    $this->logger->addInfo('--------------END OF CALL--------------');
    return $response;
});