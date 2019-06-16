<?php

namespace taobig\yii\log;

use yii\log\Logger;

class CustomLogger
{

    public static function access(string $category, string $message, bool $isBegin = false)
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
        \Yii::getLogger()->log($logBody, Logger::LEVEL_INFO, $category);

        if ($isBegin) {
            $headers = \Yii::$app->request->getHeaders();
            if ($headers) {
                \Yii::getLogger()->log("HTTP Headers:" . json_encode($headers->toArray()), Logger::LEVEL_INFO, $category);
            }
        }
    }

    public static function debug($message, string $category = 'debug')
    {
        if (!(is_string($message))) {
            $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        \Yii::getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }
}