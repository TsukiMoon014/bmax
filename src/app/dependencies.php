<?php

$container = $app->getContainer();

// Monolog service
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger($c['settings']['logger']['name']);
    $file_handler = new \Monolog\Handler\StreamHandler($c['settings']['logger']['path']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// DB service
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    return $pdo;
};

// Template service
$container['view'] = function($c){
    return new \Slim\Views\PhpRenderer($c['settings']['template']['path']);
};

// Service Checker
$container['CheckerController'] = function($c){
    return new CheckerController($c);
};