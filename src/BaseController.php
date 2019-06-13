<?php

namespace taobig\yii;

use yii\web\Controller;
use yii\web\Response;

abstract class BaseController extends Controller
{

    public function successJsonResponse($data = null, string $message = '')
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return Container::getJsonResponseFactory()->buildSuccessResponse($data, $message);
    }

    public function errorJsonResponse(string $message)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return Container::getJsonResponseFactory()->buildErrorResponse($message);
    }
}