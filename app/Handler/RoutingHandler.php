<?php

namespace Handler;

use Controller\AdminController\SecurityController;

class RoutingHandler
{
    private $routes;
    private $route;
    ////////////////
    private $authorizationRequired;
    ////////////////
    private $controller;
    private $action;
    ////////////////
    private $postParameters;
    private $getParameters;
    private $cookieParameters;

    public function __construct($jsonRoutingFile)
    {
        //$this->routes = json_decode(file_get_contents($jsonRoutingFile));
        $this->routes = json_decode(file_get_contents($jsonRoutingFile), true);
        $this->setControllerAndAction();
        $this->setRequestParameters();
    }

    private function setControllerAndAction()
    {
        if (!isset($_GET['route'])) {
            $route = 'start';
        } else {
            $route = $_GET['route'];
        }
        if ($this->matchRoute($route)) {
            $this->controller = str_replace('/', '\\', $this->routes[$route]['controller']);
            $this->action = $this->routes[$route]['action'];
            $this->route = $route;
            if (!$this->checkAuthorization()) {
                //user not authorized - should be kicked from resource
                //go to defaults
                $this->route = "notAuthorized";
                $this->controller = "Controller\\PageController\\IndexPageController";
                $this->action = "draw";
                $this->page = 'notAuthorized';
            }
            if (isset($this->post['debug'])) {
                var_dump($post);
            }
        } else {
            echo "404 - Missing controller for route " . $_GET['route'];
            exit(404);
        }
    }

    public function checkAuthorization($requiredGroupLevel = null)
    {
        $this->authorizationRequired = isset($this->routes[$this->route]['authorize']);
        if ($this->authorizationRequired) {
            if (!isset($requiredGroupLevel)) {
                $requiredGroupLevel = $this->routes[$this->route]['authorize'];
            }
            $sec = new SecurityController($this);
            return $sec->authorize($requiredGroupLevel);
        } else {
            return true;
        }
    }

    private function matchRoute(&$queryroute)
    {
        if (isset($this->routes[$queryroute])) {
            return true;
        }
        /*
        Go from this member/solbybladet ($queryroute)
        to match this member/{resource} from routes.json
        And store "solbybladet" as property: resource
        */
        $routingKeyMap = [];
        foreach ($this->routes as $route => $value) {
            $jsonRoute = $route;
            $pregPattern = '/{(.+?)}/';
            preg_match_all($pregPattern, $route, $routingKeys);
            if (count($routingKeys[1]) > 0) {
                foreach ($routingKeys[1] as $key => $value) {
                    $routingKeyMap[$jsonRoute]['property'] = $value;
                    $route = str_replace($routingKeys[0][$key], '(.+?)', $route);
                }
                $route = str_replace('/', '\/', $route);
                $route = sprintf("/%s\$/", $route);
                preg_match_all($route, $queryroute, $routingKeyValues);
                if (count($routingKeyValues[1]) > 0) {
                    foreach ($routingKeyValues[1] as $key => $value) {
                        $routingKeyMap[$jsonRoute]['value'] = $value;
                        $queryroute = $jsonRoute;
                    }
                    foreach ($routingKeyMap as $value) {
                        if (isset($value['value'])) {
                            $prop = $value['property'];
                            $this->$prop = $value['value'];
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    private function setRequestParameters()
    {
        $route = $this->route;
        if (isset($this->routes[$route]['parameters'])) {
            foreach ($this->routes[$route]['parameters'] as $key => $value) {
                $this->$key = $value;
            }
        }
        if (isset($_POST)) {
            $this->postParameters = $_POST;
        }
        if (isset($_GET)) {
            $this->getParameters = $_GET;
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getPostParameter($parameter, $default = "")
    {
        return isset($this->postParameters[$parameter]) ? $this->postParameters[$parameter] : $default;
    }

    public function getPost()
    {
        return $this->postParameters;
    }

    public function getGet()
    {
        return $this->getParameters;
    }
}
