<?php

namespace Service;

class DICService
{
    private static $objects;
    private static $invokedObjects;

    public static function register($name, $callback)
    {
        self::$objects[$name] = $callback;
    }

    public static function resolve($name)
    {
        return self::$objects[$name]->__invoke();
    }

    public static function getSessionService()
    {
        return self::resolve('session');
    }
}
