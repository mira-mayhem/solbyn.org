<?php

namespace Controller\AdminController;

use Service\AuthenticationService;

class SecurityController
{
    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function login()
    {
        $filename = 'templates/admin/login.form';
        $content = $this->render($filename);
        require "templates/admin/admin.wf.php";
    }

    public function authenticate()
    {
        $routingHandler = $this->routingHandler;
        $user = $routingHandler->getPost()['user'];
        $pass = $routingHandler->getPost()['pass'];
        $auth = new AuthenticationService();
        if ($auth->authenticateUser($user, $pass)) {
            //handle this
            global $ioc;
            $session = $ioc->getSessionService();
            $session
                ->set('auth', true)
                ->set('user', $user)
                ->set('group', $auth->UserGroup($user))
                ->setLifetime();
            echo json_encode(["userlogin" => "success", "userloginsuccess" => true]);

        } else {
            echo json_encode(["userlogin" => "failure", "userloginsuccess" => false]);
        }
    }

    public function authorize($group)
    {
        global $ioc;
        $session = $ioc->getSessionService();
        if ($session->get('auth') === true) {
            if (in_array($group, $session->get('group'))) {
                return true;
            }
        }
        return false;
    }

}
