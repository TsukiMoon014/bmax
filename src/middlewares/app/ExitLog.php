<?php

namespace bmax\src\middlewares\app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Application Middleware
// Central Log Exit Point
class ExitLog
{
	private $logger;

    public function __construct($logger) {
        $this->logger = $logger;
    }

	public function __invoke(Request $request, Response $response, callable $next) {
	    // Passing it to routing
	    // as this middleware is handling things after response is made
	    $response = $next($request, $response);

	    // Logging exiting response
	    $this->logger->addInfo('Exiting code : '.$response->getStatusCode());
	    $this->logger->addInfo('--------------END OF CALL--------------');
	    return $response;
	}
}
