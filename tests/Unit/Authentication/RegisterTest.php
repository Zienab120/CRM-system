<?php

namespace Tests\Unit\Authentication;

use App\Exceptions\RegistrationException;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    public function test_register_successful(): void
    {
        $authService = new AuthService();

        $data = [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'phone' => '01098427392',
            'role' => 'Super Admin'
        ];

        $user = $authService->register($data);

        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com'
        ]);

        $this->assertTrue($user->hasRole('Super Admin'));
    }

    public function test_register_fails_if_email_exists(): void
    {
        $authService = new AuthService();

        $data1 = [
            'name' => 'First User',
            'email' => 'test@gmail.com',
            'phone' => '01098427392',
            'password' => 'password',
            'role' => 'Super Admin'
        ];

        $data2 = [
            'name' => 'Second User',
            'email' => 'test@gmail.com',
            'phone' => '01098427393',
            'password' => 'password',
            'role' => 'Super Admin'
        ];

        $authService->register($data1);

        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
            'phone' => '01098427392',
        ]);


        $this->expectException(RegistrationException::class);
        $authService->register($data2);
    }

    public function test_register_fails_with_missing_validation(): void
    {
        $authService = new AuthService();

        $data = [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password'
        ];

        $this->expectException(RegistrationException::class);
        $authService->register($data);
    }
}
