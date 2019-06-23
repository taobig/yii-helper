<?php

namespace taobig\yii\exceptions;

use taobig\yii\Container;
use Throwable;

abstract class BaseException extends \Exception
{
    protected $exposeErrorMessage = false;
    protected $extraData = null;

    public function getExposeErrorMessage(): bool
    {
        return $this->exposeErrorMessage;
    }

    public function getExtraData()
    {
        return $this->extraData;
    }

    public function __construct(string $message = "", int $code = null, Throwable $previous = null, $extraData = null)
    {
        if ($code === null) {
            $code = Container::getJsonResponseFactory()->getCommonErrorStatus();
        }
        $this->extraData = $extraData;

        parent::__construct($message, $code, $previous);
    }

}