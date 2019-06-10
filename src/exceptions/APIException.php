<?php

namespace taobig\yii\exceptions;

use taobig\yii\JsonResponseConstants;

class APIException extends BaseException
{

    private $_url;
    private $_response;
    private $_request;

    /**
     * APIException constructor.
     * @param string $message
     * @param string $url
     * @param array|string $response
     * @param array|string $request
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message, string $url, $response, $request, int $code = JsonResponseConstants::CODE_API_INVOKE_ERROR, \Exception $previous = null)
    {
        $this->_url = $url;
        $this->_response = $response;
        $this->_request = $request;

        parent::__construct($message, $code, $previous);
    }

    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @return array|string
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return array|string
     */
    public function getResponse()
    {
        return $this->_response;
    }

    public function getLoggedExceptionMessage(): string
    {
        $message = ' ' . $this->getUrl() . ' ' . json_encode($this->getRequest());
        $response = $this->getResponse();
        if (!is_string($response)) {
            $message .= ' ' . json_encode($response);
        } else {
            $message .= ' ' . $response;
        }
        return $message;
    }
}