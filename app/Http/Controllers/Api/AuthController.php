<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\NotAuthenticatedException;
use App\Exceptions\RegistrationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $response = $this->authService->authenticate($request);
            return $this->successResponse($response, 'Login successful.');
        } catch (InvalidCredentialsException $e) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode() ?: 400);
        }
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        try {
            $response = $this->authService->register($data);
            return $this->successResponse($response, 'Registration successful.', 201);
        } catch (RegistrationException $e) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode() ?: 400);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return $this->successResponse(null, 'Logout successful.');
        } catch (NotAuthenticatedException $e) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode() ?: 400);
        }
    }
}
