<?php

namespace App\Exceptions;

use App\Exceptions\BaseApiException;
use Exception;
use Throwable;

class Handler extends Exception
{
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {

            if ($e instanceof BaseApiException) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage(),
                    'errors'  => $e->getErrors(),
                ], $e->getStatusCode());
            }

            if ($e instanceof InvalidCredentialsException) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }

            if ($e instanceof RegistrationException) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }

             if ($e instanceof NotAuthenticatedException) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication failed. Please log in again.',
                ], 401); // Unauthorized
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $e->errors(), // Include validation errors
                ], 422); // Unprocessable Entity
            }

            return response()->json([
                'status'  => false,
                'message' => 'Server error',
            ], 500);
        }

        return parent::render($request, $e);
    }
}
