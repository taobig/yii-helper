<?php

namespace taobig\yii\handlers;

class ConsoleErrorHandler extends \yii\base\ErrorHandler
{

    public function renderException($exception)
    {
        print_r($exception->__toString());
    }


    public function logException($exception)
    {
        \Yii::error($exception);
    }

}
