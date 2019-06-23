<?php

namespace taobig\yii;

interface JsonResponseFactoryInterface
{

    public function buildSuccessResponse($data = null, string $message = ''): array;

    public function buildErrorResponse(string $message, $data = null, int $status = null): array;

    public function getNoErrorStatus(): int;

    public function getCommonErrorStatus(): int;

    public function isJsonResponse(): bool;

}