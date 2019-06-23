<?php

namespace taobig\yii\exceptions;

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
     */
    public function __construct(string $message, string $url, $response, $request)
    {
        $this->_url = $url;
        $this->_response = $response;
        $this->_request = $request;

        parent::__construct($message);
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

    public function __toString()
    {
        $additionalMessage = '';
        if ($this->getUrl()) {
            $additionalMessage .= 'Url:' . json_encode($this->getUrl(), JSON_UNESCAPED_UNICODE);
            $additionalMessage .= ',';
        }
        if ($this->getRequest()) {
            $additionalMessage .= 'Request:' . json_encode($this->getRequest(), JSON_UNESCAPED_UNICODE);
            $additionalMessage .= ',';
        }
        if ($this->getResponse()) {
            $additionalMessage .= 'Response:' . json_encode($this->getResponse(), JSON_UNESCAPED_UNICODE);
            $additionalMessage .= ',';
        }
        return $additionalMessage . parent::__toString();
    }
}