<?php

namespace taobig\yii;

final class Container
{

    private static $_definitions = [];
    private static $_singletons = [];


    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function setJsonResponseFactory(string $className)
    {
        self::$_definitions[JsonResponseFactoryInterface::class] = $className;
    }

    /**
     * @return JsonResponseFactoryInterface
     */
    public static function getJsonResponseFactory()
    {
        if (empty(self::$_singletons[JsonResponseFactoryInterface::class])) {
            if (isset(self::$_definitions[JsonResponseFactoryInterface::class])) {
                self::$_singletons[JsonResponseFactoryInterface::class] = new self::$_definitions[JsonResponseFactoryInterface::class];
            } else {
                self::$_singletons[JsonResponseFactoryInterface::class] = new DefaultJsonResponseFactory();
            }
        }
        return self::$_singletons[JsonResponseFactoryInterface::class];
    }

}