<?php

use Handler\RoutingHandler;
$routingFile = __DIR__ . '/routes.json';
$routingHandler = new RoutingHandler($routingFile);
$controller = $routingHandler->getController();
$method = $routingHandler->getAction();

$c = new $controller($routingHandler);
$c->$method();

