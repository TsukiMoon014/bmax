<?php

namespace bmax\src\controllers;

class CheckerController
{
	protected $pdo;

	function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function check(Request $request, Response $response, array $args){
		$req = $this->db->prepare("SELECT * from table_test");
		$req->execute();
		$result = $req->fetchAll();

		return $this->view->render($response, 'check.phtml', ['result' => $result]);
	}
}