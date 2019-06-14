<?php

namespace taobig\yii\log;

use yii\helpers\VarDumper;
use yii\log\Logger;

class FileTarget extends \yii\log\FileTarget
{

    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Throwable || $text instanceof \Exception) {
                $text = (string)$text;
            } else {
                $text = VarDumper::export($text);
            }
        }
        $traces = [];
        if (isset($message[4])) {
            foreach ($message[4] as $trace) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }

        $prefix = $this->getMessagePrefix($message);

        $log = [
            'project_name' => \Yii::$app->name,
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'message' => str_replace([';', "\r\n", "\n", "\r"], ',', $text),
            'http_referer' => $_SERVER['HTTP_REFERER'] ?? '',
        ];
        $logBody = implode(";", $log);

        $uniqueId = UniqueRequest::getId();
        return $this->getTime($timestamp) . " [{$uniqueId}]{$prefix}[$level][$category] $logBody" . (empty($traces) ? '' : ";" . implode(",", $traces));
    }

}
