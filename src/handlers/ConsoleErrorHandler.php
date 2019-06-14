<?php

namespace taobig\yii\handlers;

use taobig\yii\exceptions\BaseException;

class ConsoleErrorHandler extends \yii\base\ErrorHandler
{

    public function renderException($exception)
    {
        print_r($exception->__toString());
    }


    public function logException($exception)
    {
        $message = $exception->getMessage();
        if ($this instanceof BaseException) {
            $message = $this->getLoggedExceptionMessage();
        }
        \Yii::error($message);
    }

}
