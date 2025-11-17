<?php

namespace App\Exceptions;

use Exception;

abstract class BaseApiException extends Exception
{
    protected int $statusCode = 400;
    protected array $errors = [];

    public function __construct(string $message = null, array $errors = [], int $statusCode = null)
    {
        parent::__construct($message ?? $this->defaultMessage());

        if ($statusCode) {
            $this->statusCode = $statusCode;
        }

        $this->errors = $errors;
    }

    /**
     * Default message for the exception
     */
    protected function defaultMessage(): string
    {
        return 'An error occurred.';
    }

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the detailed error list
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
