<?php

namespace bmax\src\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CheckerController
{
	protected $view;
	protected $db;

	function __construct($view, $db)
	{
		var_dump($view);
		var_dump($db);
		$this->view = $view;
		$this->db = $db;
	}

	public function checker(Request $request, Response $response, array $args){
		$req = $this->db->prepare("SELECT * from table_test");
		$req->execute();
		$result = $req->fetchAll();

		return $this->view->render($response, 'check.phtml', ['result' => $result]);
	}
}