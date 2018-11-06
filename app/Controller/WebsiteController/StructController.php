<?php

namespace Controller\WebsiteController;

class StructController
{
    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function addpage()
    {
        //var_dump($this->routingHandler);
        $routesFile = 'config/routes.json';
        if (file_exists($routesFile)) {
            $routesJson = file_get_contents($routesFile);
            require 'templates/page/addpage.php';
        } else {
            echo "file missing";
            echo __DIR__;
            echo dirname('/');
        }
    }
}
