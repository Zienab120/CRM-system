<?php

namespace App\Exceptions;

use App\Exceptions\BaseApiException;

class NotAuthenticatedException extends BaseApiException
{
    protected int $statusCode = 401;

    protected function defaultMessage(): string
    {
        return 'User is not authenticated.';
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
