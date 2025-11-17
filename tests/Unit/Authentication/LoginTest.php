<?php

namespace Tests\Unit\Authentication;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successful(): void
    {
        $user = User::factory()->create([
            'name' => 'test user',
            'email' => 'testing@gmail.com',
            'password' => 'secret.password',
            'phone' => '01091410205',
        ]);

        $request = new LoginRequest([
            'email' => 'testing@gmail.com',
            'password' => 'secret.password',
            'remember' => true,
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $authService = new AuthService();
        $authenticatedUser = $authService->authenticate($request);

        $this->assertEquals($user->id, $authenticatedUser->id);
        $this->assertNotNull($authenticatedUser->last_login_at);
    }

    public function test_authenticate_fails_with_invalid_credentials(): void
    {
        $authService = new AuthService();

        $request = new LoginRequest([
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $request->setLaravelSession($this->app['session.store']);

        $this->expectException(InvalidCredentialsException::class);
        $authService->authenticate($request);
    }
}
