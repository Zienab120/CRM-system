
<?php

namespace Tests\Unit\Services;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\NotAuthenticatedException;
use App\Exceptions\RegistrationException;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;


class AuthServiceTest extends BaseTestCase
{
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_authenticate_with_valid_credentials()
    {
        $request = $this->createMock('Illuminate\Http\Request');
        $request->method('only')->willReturn(['email' => 'test@example.com', 'password' => 'password']);
        $request->method('boolean')->willReturn(false);
        $request->method('session')->willReturn($this->createMock('Illuminate\Session\Store'));

        Auth::shouldReceive('attempt')->andReturn(true);
        Auth::shouldReceive('user')->andReturn(factory(User::class)->make());

        $user = $this->authService->authenticate($request);
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_authenticate_with_invalid_credentials()
    {
        $request = $this->createMock('Illuminate\Http\Request');
        $request->method('only')->willReturn(['email' => 'test@example.com', 'password' => 'wrong']);
        $request->method('boolean')->willReturn(false);

        Auth::shouldReceive('attempt')->andReturn(false);

        $this->expectException(InvalidCredentialsException::class);
        $this->authService->authenticate($request);
    }

    public function test_register_success()
    {
        $data = ['email' => 'new@example.com', 'password' => 'password', 'role' => 'user'];
        
        $user = $this->authService->register($data);
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_logout_success()
    {
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('logout');

        $this->authService->logout();
        $this->assertTrue(true);
    }

    public function test_logout_when_not_authenticated()
    {
        Auth::shouldReceive('check')->andReturn(false);

        $this->expectException(NotAuthenticatedException::class);
        $this->authService->logout();
    }
}