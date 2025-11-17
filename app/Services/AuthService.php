<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\NotAuthenticatedException;
use App\Exceptions\RegistrationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function authenticate($request)
    {
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember')))
            throw new InvalidCredentialsException();

        $user = Auth::user();
        $user->update(['last_login_at' => now()]);

        $request->session()->regenerate();

        return $user;
    }

    public function register($data)
    {
        return rescue(function () use ($data) {
            $user = User::create($data);
            $user->assignRole($data['role']);
            return $user;
        }, report: function (\Throwable $e) {
            throw new RegistrationException();
        });
    }

    public function logout()
    {
        if (!Auth::check())
            throw new NotAuthenticatedException();

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
