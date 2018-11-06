<?php

namespace Controller\PageController;

use Service\ContentService;

class IndexPageController
{
    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function draw()
    {
        $routingHandler = $this->routingHandler;
        $page = $routingHandler->page;
        $contentService = new ContentService($routingHandler);
        $routingHandler->type = isset($routingHandler->type) ? $routingHandler->type : null;
        $content = $contentService->getPageContent($page, $routingHandler->type);
        $submenu = $contentService->getSubmenu();
        $imgMap = [
        ];
        $activeLinkMap = [
            'start' => '',
            'about' => '',
            'history' => '',
            'contact' => '',
            'gallery' => ''
        ];
        if (!isset($imgMap[$page])) {
            $imgMap[$page] = $this->randomizeImage();
        }
        $activeLinkMap[$page] = 'active';
        require "templates/index.wf.php";
    }

    private function randomizeImage()
    {
        $imgGallery = [];
        $dir = 'images/gallery';
        if ($handle = opendir($dir)) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $imgGallery[] = $file;
            }
            closedir($handle);
        }
        $randomInt = random_int(0, -1 + count($imgGallery));
        return $imgGallery[$randomInt];
    }
}
