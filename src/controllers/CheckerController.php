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

	public function checker(Request $request, Response $response, array $args){
		$force_update = isset($args['force_update']);
		$req = $this->container->db->prepare("
			SELECT *
			FROM control
			ORDER BY control_id DESC
			LIMIT 1
		");
		$req->execute();
		$result = $req->fetch();
		$today = new \DateTime();
		$last_update = new \DateTime($result['last_update']);
		$days_without_update = $today->diff($last_update);

		// experimental
		$eve_current_version = 1243162;

		$new_version_available = false;
		$new_prices_available = false;

		// A new version is available, we need to update the item base
		if((int)$result['eve_version_number'] !== $eve_current_version)
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'item'
		    ]);
		    $new_version_available = true;
		}
		// It's been a day without action, we need to update prices
		elseif($days_without_update->format("%R") === "-")
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'price'
		    ]);
		    $new_prices_available = true;
		}
		// We can update anyway if we want
		else
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'price'
		    ]);
		}

		return $this->container->view->render($response, 'check.phtml', [
			'today' 				=> $today,
			'last_update' 			=> $last_update,
			'days_without_update' 	=> $days_without_update,
			'new_version_available' => $new_version_available,
			'new_prices_available'	=> $new_prices_available,
			'router' 				=> $this->container->get('router')
		]);
	}
}