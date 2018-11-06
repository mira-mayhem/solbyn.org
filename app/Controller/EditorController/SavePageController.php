<?php

namespace Controller\EditorController;

use Handler\RoutingHandler;

class SavePageController
{
    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function save()
    {
        $routingHandler = $this->routingHandler;
        $post = $routingHandler->getPost();
        $contentFile = sprintf("templates/%s.inc.html", $post['page_name']);
        $data = sprintf($this->outerDiv($post['page_type']), $post['page_content']);
        file_put_contents($contentFile, $data);
    }

    private function outerDiv($pageType)
    {
        switch ($pageType) {
            case 'article':
                return "<div class='article'>%s</div>";
                break;

            default:
                # code...
                break;
        }
    }
}
