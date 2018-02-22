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
		// Update item database

		// First call also gets the number of pages
		// in header x-page
		$page = 0;
		do{
			// First we get item id 1000 per 1000
			$resItem = $this->container->CurlHelper->get('universe/types',[
				'query' => [
					'datasource' => 'tranquility',
					'page'		 => $page
				]
			]);
			if($resItem->getStatusCode() === 200){
				$itemList = array_values(json_decode($resItem->getBody(),true));
				// Then we get info on them
				var_dump($itemList);


				$resInfo = $this->container->CurlHelper->request('POST','universe/names',[
					'headers' => [
						'Content-Type' => 'application/x-www-form-urlencoded'
					],
					'form_params' => [
						'body' => $itemList
					]
				]);

				var_dump($resInfo->getBody());
				/*
				foreach ($itemList as $item) {
					$req = $this->container->db->prepare("
						SELECT *
						FROM items
						WHERE item_id = :item_id");
					$req->bindValue(":item_id",$item['']);
				}*/

			}else{
				var_dump("ERROR");
			}
			$page++;


		}while ($page < 0);//(int)$resItem->getHeader('x-pages')[0]);

		// Update version control history
		return "MAJ de la version";
	}

	function updateMarketData()
	{
		return "MAJ du market";
	}
}
