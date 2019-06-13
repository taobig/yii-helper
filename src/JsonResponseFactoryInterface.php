<?php

namespace taobig\yii;

interface JsonResponseFactoryInterface
{
    
    public function buildSuccessResponse($data = null, string $message = ''): array;

    public function buildErrorResponse(string $message, $data = null, int $status = JsonResponseConstants::CODE_COMMON_ERROR): array;

    public function isJsonResponse(): bool;

}