<?php

namespace taobig\yii\exceptions;

use taobig\yii\JsonResponseFactory;
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

    public function getLoggedExceptionMessage(): string
    {
        return $this->message;
    }

    public function __construct(string $message = "", int $code = JsonResponseFactory::CODE_COMMON_ERROR, Throwable $previous = null, $extraData = null)
    {
        $this->extraData = $extraData;

        parent::__construct($message, $code, $previous);
    }

}