<?php

namespace taobig\yii\handlers;

use taobig\yii\Container;
use taobig\yii\exceptions\BaseException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ErrorHandler extends \yii\web\ErrorHandler
{

    public function renderException($exception)
    {
        if (YII_ENV_DEV) {
            $errorMessage = $exception->__toString();
        } else {
            $errorMessage = Yii::t('app', 'System error, please try again later');
            if ($exception instanceof BaseException) {
                if ($exception->getExposeErrorMessage()) {
                    $errorMessage = $exception->getMessage();
                }
            } else {//other exceptions(include framework exception)
                if ($exception instanceof NotFoundHttpException) {
                    $errorMessage = $exception->getMessage();//Page not found.
                }
            }
        }

        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        if (Container::getJsonResponseFactory()->isJsonResponse() || $response->format == Response::FORMAT_JSON) {
            $response->setStatusCode(200);//保证返回格式为json时，HTTP状态码总是200
            if ($response->format != Response::FORMAT_JSON) {
                $response->format = Response::FORMAT_JSON;
            }

            $errorData = null;
            if ($exception instanceof BaseException) {
                $errorData = $exception->getExtraData();
            }

            if ($exception instanceof BaseException) {
                $response->data = Container::getJsonResponseFactory()->buildErrorResponse($errorMessage, $errorData, $exception->getCode());
            } else {
                $response->data = Container::getJsonResponseFactory()->buildErrorResponse($errorMessage, $errorData);
            }
        } else {
            $response->setStatusCodeByException($exception);
            $response->data = Yii::$app->view->render('@app/views/layouts/error', ['name' => Yii::t('app', 'Error'), 'message' => $errorMessage]);
        }
        $response->send();
    }


    public function logException($exception)
    {
        if ($exception instanceof NotFoundHttpException) {//don't log
            return;
        }
//        $category = get_class($exception);
//        if ($exception instanceof HttpException) {
//            $category = 'yii\\web\\HttpException:' . $exception->statusCode;
//        } elseif ($exception instanceof \ErrorException) {
//            $category .= ':' . $exception->getSeverity();
//        }
        \Yii::error($exception);
    }

}