<?php

namespace Controller\PageController;

use Service\FileService;

class AjaxController
{

    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function load()
    {
        $method = sprintf("load%s", ucfirst($this->routingHandler->load));
        if (method_exists($this, $method)) {
            echo $this->$method();
        } else {
            echo $method . " missing in AjaxController";
        }
    }

    private function loadSolbybladet()
    {
        $fs = new FileService();
        $dir = "uploads/solbybladet/";
        $files = $fs->getFiles($dir);
        $htmlList = $fs->createFileList($files, $dir);
        return $htmlList;
    }
}
