<?php 

namespace App\Exceptions;

use App\Exceptions\BaseApiException;

class InvalidCredentialsException extends BaseApiException
{
    protected int $statusCode = 401;

    protected function defaultMessage(): string
    {
        return 'Invalid credentials provided.';
    }
}