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
		$eve_current_version = 1247568;
		$a = $this->container->get('CurlHelper');
var_dump($a->base_uri);
$client = $this->container->get('CurlHelper')->get('https://esi.tech.ccp.is/latest', [
    'query' => [
    	'datasource' => 'tranquility'
    ],
    'headers' => [
        'User-Agent' => 'bmax/1.0',
        'Accept'     => 'application/json'
    ]
]);

		/*$apiStatusResponse = $this->container->get('CurlHelper')->request('GET', 'status',[
			'query' => ['data_source' => 'tranquility'],
		]);

		$body = $apiStatusResponse->getBody();
*/
		$new_version_available = false;
		$new_prices_available = false;

		// A new version is available, we need to update the item base
		if((int)$result['eve_version_number'] < $eve_current_version)
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'version'
		    ]);
		    $new_version_available = true;
		}
		// It's been a day without action, we need to update prices
		elseif($days_without_update->format("%R") === "-")
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'market'
		    ]);
		    $new_prices_available = true;
		}
		// We can update anyway if we want
		else
		{
			$update_link = $this->container->get('router')->pathFor('update', [
			    'scale' => 'market'
		    ]);
		}

		return $this->container->view->render($response, 'check.phtml', [
			'today' 				=> $today,
			'last_update' 			=> $last_update,
			'days_without_update' 	=> $days_without_update,
			'new_version_available' => $new_version_available,
			'new_prices_available'	=> $new_prices_available,
			'update_link'			=> $update_link,
			'stat'					=> $apiStatusResponse
		]);
	}
}