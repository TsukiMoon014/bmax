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
				// unfortunatly it can be a huge update
				set_time_limit(60*60);
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
		$page = 0;
		$maxpage = 0;
		$itemIdList = array();

		do{
			// First we get item id 1000 per 1000
			$resItem = $this->container->CurlHelper->get('universe/types/',[
				'query' => [
					'datasource' => 'tranquility',
					'page'		 => $page
				]
			]);

			if($resItem->getStatusCode() === 200){
				// First call also gets the number of pages
				// in header x-page
				if($page === 0){
					$maxpage = (int)$resItem->getHeader('x-pages')[0];
				}

				// Get item list of item on the market
				$itemIdList = array_unique(array_merge($itemIdList,array_values(array_unique(json_decode($resItem->getBody(),true)))));

			}else{
				var_dump("ERROR");
			}
			$page++;
		}while ($page <= $maxpage);

		// Then we get info on them, 1000 per 1000
		$subSet = array();
		$start = 0;
		$step = 1000;
		$nb_update = 0;
		$nb_unchanged = 0;
		do{
			$subSet = array_slice($itemIdList,$start,$step);
			$start += $step;

			$resInfo = $this->container->CurlHelper->post('universe/names/',[
				"headers" => [
					"Content-Type" => "application/json"
				],
				\GuzzleHttp\RequestOptions::JSON => array_values($subSet)
			]);

			$itemListDetailed = json_decode($resInfo->getBody(),true);

			// Does it exists in our database ?
			foreach ($itemListDetailed as $item) {
				$req = $this->container->db->prepare("
					SELECT *
					FROM items
					WHERE item_id = :item_id");
				$req->bindValue(":item_id",$item['id']);

				$req->execute();
				$dbItem = $req->fetch();

				// It doesn't or it's not accurate, let's create or update it
				if(empty($dbItem) || $dbItem['name'] != $item['name'] || $dbItem['category'] != $item['category']){
					$req = $this->container->db->prepare("
						INSERT INTO items
						(item_id, name, category)
						VALUES(:item_id, :name, :category)
				    ON DUPLICATE KEY
				    UPDATE
				    	name = :name2,
				    	category = :category2");

					$req->bindParam(':item_id', $item['id']);
					$req->bindParam(':name', $item['name']);
					$req->bindParam(':name2', $item['name']);
					$req->bindParam(':category', $item['category']);
					$req->bindParam(':category2', $item['category']);

					if($req->execute()){
						$nb_update++;
					}
				}else{
					$nb_unchanged++;
				}
			}
		}while (count($subSet) == $step);
		return $nb_update;
	}

	function updateMarketData()
	{
		// First call also gets the number of pages
	    // in header x-page
	    $page = 0;
	    $maxpage = 0;
	    $nb_update = 0;
		do{
			// First call also gets the number of pages
			// in header x-page
			if($page === 0){
				$maxpage = (int)$resItem->getHeader('x-pages')[0];
			}

			$resItem = $this->container->CurlHelper->get('markets/'.$this->container->get('settings')['utils']['the_forge_region_id'].'/orders/',[
		        'query' => [
		          'datasource' => 'tranquility',
		          'order_type' => 'sell',
		          'page'     => $page
		        ]
		      ]);

			//@TODO : only keep jita 4_4 sell orders
		}while ($page <= $maxpage);
	}
}
