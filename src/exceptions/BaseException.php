<?php

namespace taobig\yii\exceptions;

use taobig\yii\JsonResponseConstants;
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

    public function __construct(string $message = "", int $code = JsonResponseConstants::CODE_COMMON_ERROR, Throwable $previous = null, $extraData = null)
    {
        $this->extraData = $extraData;

        parent::__construct($message, $code, $previous);
    }

}