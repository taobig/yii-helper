<?php
namespace taobig\yii;

use taobig\yii\filters\VerbFilter;
use yii\web\JsonParser;
use yii\web\Response;

class BaseJsonController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    '*' => ['post'],
                ],
            ],
        ];
    }

    public function init()
    {
        \Yii::$app->request->enableCsrfValidation = false;
        \Yii::$app->request->parsers = [
            'application/json' => JsonParser::class,
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

}