<?php

namespace bmax\src\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UpdateController
{
	protected $container;

	function __construct($container)
	{
		$this->container = $container;
	}

	function updateSelector(Request $request, Response $response, array $args)
	{
		$scale = $args['scale'];
		switch ($scale) {
			// Update all items because Eve has a new version
			case 'version':
				$success = $this->updateVersion();
				break;

			// Update all market prices
			case 'market':
				$success = $this->updateMarketData();
				break;

			// scale not yet implemented
			default:
				throw new \Exception();
				break;
		}

		return $this->container->view->render($response, 'update.phtml', [
			'scale' 	=> $scale,
			'success' 	=> $success
		]);
	}

	function updateVersion()
	{
		return "MAJ de la version";
	}

	function updateMarketData()
	{
		return "MAJ du market";
	}
}
