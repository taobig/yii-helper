<?php

namespace taobig\yii;

class DefaultJsonResponseFactory implements JsonResponseFactoryInterface
{

    const CODE_NO_ERROR = 0;
    const CODE_COMMON_ERROR = 1;

    public function buildSuccessResponse($data = null, string $message = ''): array
    {
        return self::buildData($message, self::getNoErrorStatus(), $data);
    }

    public function buildErrorResponse(string $message, $data = null, int $status = null): array
    {
        if ($status === null) {
            $status = self::getCommonErrorStatus();
        }
        return self::buildData($message, $status, $data);
    }

    private function buildData(string $message, int $status, $data = null): array
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return $result;
    }

    public function getNoErrorStatus(): int
    {
        return self::CODE_NO_ERROR;
    }

    public function getCommonErrorStatus(): int
    {
        return self::CODE_COMMON_ERROR;
    }

    public function isJsonResponse(): bool
    {
        return (!empty($_SERVER['HTTP_X_RESPONSE_TYPE'])) && $_SERVER['HTTP_X_RESPONSE_TYPE'] == 'json';
    }

}