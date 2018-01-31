<?php

namespace bmax\src\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CheckerController
{
	protected $container;

	function __construct($container)
	{
		$this->container = $container;
	}

	public function check(Request $request, Response $response, array $args){
		$req = $this->container->db->prepare("SELECT * from table_test");
		$req->execute();
		$result = $req->fetchAll();

		return $this->container->view->render($response, 'check.phtml', ['result' => $result]);
	}
}