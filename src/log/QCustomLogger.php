<?php


namespace taobig\yii\log;


use yii\log\Logger;


class QCustomLogger
{
    const TYPE_ERROR = Logger::LEVEL_ERROR;
    const TYPE_INFO = Logger::LEVEL_INFO;
    const TYPE_DEBUG = Logger::LEVEL_TRACE;

    const CATEGORY_ACCESS = 'access';
    const CATEGORY_DEBUG = 'debug';

    protected static function getLogger()
    {
        return \Yii::getLogger();
    }

    public static function logException(\Throwable $e, string $message = '')
    {
        self::log(self::TYPE_ERROR, $message ?: $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
    }

    public static function error(string $message)
    {
        self::log(self::TYPE_ERROR, $message);
    }

    public static function log(string $error_type, string $message, string $filename = '', int $line = 0, string $trace = '')
    {
        $log = [
            'project_name' => \Yii::$app->name,
            'unique_id' => QUniqueRequest::getId(),
            'error_type' => $error_type,
            'datetime' => date('Y-m-d H:i:s'),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'message' => str_replace([';', "\r\n", "\n", "\r"], ',', $message),
            'filename' => $filename,
            'line' => $line,
            'trace' => str_replace([';', "\r\n", "\n", "\r"], ',', $trace),
            'http_referer' => $_SERVER['HTTP_REFERER'] ?? '',
        ];

        $logBody = implode(";", $log) . "\n";
        self::getLogger()->log($logBody, self::TYPE_ERROR);
    }

    public static function access(string $message, bool $isBegin = false)
    {
        $dt = date('Y-m-d H:i:s');
        if ($isBegin) {
            $url = $_SERVER['REQUEST_URI'];
            $method = $_SERVER['REQUEST_METHOD'];
            $remote_ip = $_SERVER['REMOTE_ADDR'];
            $title = QUniqueRequest::getId() . "\n[{$dt}] [{$remote_ip}] [{$method}] [{$url}] " . PHP_EOL;
            $logBody = $title . $message . PHP_EOL;
        } else {
            $logBody = QUniqueRequest::getId() . "\n {$dt}>>> " . $message . PHP_EOL . PHP_EOL;
        }

        self::getLogger()->log($logBody, self::TYPE_ERROR, self::CATEGORY_ACCESS);
    }

    public static function debug($message, string $category = self::CATEGORY_DEBUG)
    {
        if (!(is_string($message))) {
            $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        self::getLogger()->log($message, self::TYPE_ERROR, $category);
    }
}