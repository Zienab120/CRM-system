<?php 

namespace App\Exceptions;

use App\Exceptions\BaseApiException;

class RegistrationException extends BaseApiException
{
    protected int $statusCode = 422;

    protected function defaultMessage(): string
    {
        return 'Registration failed due to invalid data.';
    }
}