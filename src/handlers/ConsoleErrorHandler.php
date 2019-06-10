<?php

namespace taobig\yii\handlers;

use taobig\yii\exceptions\BaseException;
use taobig\yii\log\QCustomLogger;

class ConsoleErrorHandler extends \yii\base\ErrorHandler
{

    public function renderException($exception)
    {
        print_r($exception->__toString());
    }


    public function logException($exception)
    {
        $message = '';
        if ($this instanceof BaseException) {
            $message .= $this->getLoggedExceptionMessage();
        }
        QCustomLogger::logException($exception, $message);
    }

}
