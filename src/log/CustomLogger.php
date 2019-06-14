<?php

namespace taobig\yii\log;

use yii\log\Logger;

class CustomLogger
{

    const CATEGORY_ACCESS = 'access';
    const CATEGORY_DEBUG = 'debug';

    protected static function getLogger()
    {
        return \Yii::getLogger();
    }

    public static function access(string $message, bool $isBegin = false)
    {
        $dt = date('Y-m-d H:i:s');
        if ($isBegin) {
            $url = $_SERVER['REQUEST_URI'];
            $method = $_SERVER['REQUEST_METHOD'];
            $remote_ip = $_SERVER['REMOTE_ADDR'];
            $title = UniqueRequest::getId() . "\n[{$dt}] [{$remote_ip}] [{$method}] [{$url}] " . PHP_EOL;
            $logBody = $title . $message . PHP_EOL;
        } else {
            $logBody = UniqueRequest::getId() . "\n {$dt}>>> " . $message . PHP_EOL . PHP_EOL;
        }

        self::getLogger()->log($logBody, Logger::LEVEL_INFO, self::CATEGORY_ACCESS);
    }

    public static function debug($message, string $category = self::CATEGORY_DEBUG)
    {
        if (!(is_string($message))) {
            $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        self::getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }
}