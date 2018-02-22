<?php

namespace bmax\src\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CheckerController
{
	protected $container;

	function __construct($container)
	{
		$this->container = $container;
	}

	public function checker(Request $request, Response $response, array $args){
		$force_update = isset($args['force_update']);

		// Checking registered eve version in database
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


		// Getting current eve version from ccp
		$res = $this->container->CurlHelper->get('status',['query' => ['datasource' => 'tranquility']]);
		if($res->getStatusCode() === 200){
			$res_body = json_decode($res->getBody(),true);
		}else{
			var_dump("ERROR");
		}

		$new_version_available = false;
		$new_prices_available = false;

		// A new version is available, we need to update the item base
		if($result['eve_version_number'] < $res_body['server_version'])
		{
			$update_link = $this->container->router->pathFor('update', [
			    'scale' => 'version'
		    ]);
		    $new_version_available = true;
		}
		// It's been a day without action, we need to update prices
		elseif($days_without_update->format("%R") === "-")
		{
			$update_link = $this->container->router->pathFor('update', [
			    'scale' => 'market'
		    ]);
		    $new_prices_available = true;
		}
		// We can update anyway if we want
		else
		{
			$update_link = $this->container->router->pathFor('update', [
			    'scale' => 'market'
		    ]);
		}

		return $this->container->view->render($response, 'check.phtml', [
			'today' 				=> $today,
			'last_update' 			=> $last_update,
			'days_without_update' 	=> $days_without_update,
			'new_version_available' => $new_version_available,
			'new_prices_available'	=> $new_prices_available,
			'update_link'			=> $update_link
		]);
	}
}