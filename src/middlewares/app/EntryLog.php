<?php

namespace bmax\src\middlewares\app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Exception\NotFoundException;

// Application Middleware
// Central Log Entry Point
class EntryLog
{
	private $logger;

    public function __construct($logger) {
        $this->logger = $logger;
    }

	public function __invoke(Request $request, Response $response, callable $next) {
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

	    // Passing it to exit middleware
	    return $next($request, $response);
	}
}
