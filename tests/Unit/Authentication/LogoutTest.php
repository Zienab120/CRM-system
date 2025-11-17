<?php

namespace Tests\Unit\Authentication;

use App\Exceptions\NotAuthenticatedException;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_successful(): void
    {
        $user = User::factory()->create([
            'name' => 'test user',
            'email' => 'testing@gmail.com',
            'password' => 'secret.password',
            'phone' => '01091410205',
        ]);

        Auth::login($user);

        $authService = new AuthService();
        $authService->logout();

        $this->assertFalse(Auth::check());
    }

    public function test_logout_fails_if_not_authenticated(): void
    {
        $authService = new AuthService();

        $this->expectException(NotAuthenticatedException::class);

        $authService->logout();
    }
}
