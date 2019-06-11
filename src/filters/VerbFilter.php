<?php

namespace taobig\yii\filters;

use taobig\yii\exceptions\MethodNotAllowedHttpException;
use yii\base\ActionEvent;

class VerbFilter extends \yii\filters\VerbFilter
{

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        try {
            return parent::beforeAction($event);
        } catch (\yii\web\MethodNotAllowedHttpException $e) {
            throw new MethodNotAllowedHttpException($e->getMessage());
        }
    }
}
