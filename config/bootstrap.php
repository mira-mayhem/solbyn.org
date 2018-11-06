<?php

spl_autoload_register(function ($class) {
    try {
        require "app/" . str_replace('\\', '/', $class) . ".php";
    } catch (\Exception $e) {
        throw $e;
    }
});

$ioc = new \Service\DICService();
$ioc::register('session', function(){
    return new \Service\SessionService();
});
