<?php

namespace taobig\yii;

class DefaultJsonResponseFactory implements JsonResponseFactoryInterface
{

    public static function buildSuccessResponse($data = null, string $message = ''): array
    {
        return self::buildData($message, JsonResponseConstants::CODE_NO_ERROR, $data);
    }

    public static function buildErrorResponse(string $message, $data = null, int $status = JsonResponseConstants::CODE_COMMON_ERROR): array
    {
        return self::buildData($message, $status, $data);
    }

    private static function buildData(string $message, int $status, $data = null): array
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return $result;
    }

    public static function isJsonResponse(): bool
    {
        return (!empty($_SERVER['HTTP_X_RESPONSE_TYPE'])) && $_SERVER['HTTP_X_RESPONSE_TYPE'] == 'json';
    }

}