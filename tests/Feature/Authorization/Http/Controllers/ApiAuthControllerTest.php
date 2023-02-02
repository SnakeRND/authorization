<?php

namespace Tests\Feature\Authorization\Http\Controllers;

use App\Modules\Authorization\Models\User;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    private function fillTable()
    {
        User::factory()->create([
            'login' => '79998887766',
            'email' => 'test1@test.ru',
            'password' => '12345678',
            'verification_code' => '123456',
        ]);

        User::factory()->create([
            'login' => '79998887777',
            'email' => 'test2@test.ru',
            'password' => '12345678',
            'verification_code' => '123456',
        ]);

        User::factory()->create([
            'login' => '79998887788',
            'email' => 'test3@test.ru',
            'password' => '12345678',
            'verification_code' => '123456',
        ]);
    }

    public function testGetVerificationCode()
    {
        $this->fillTable();

        $registerUser = [
            'login' => '79998887755'
        ];

        $this->post('/api/v1/get_verification_code', $registerUser);

        $this->assertDatabaseCount('auth.users', 4);
    }

    public function testGetVerificationCodeNegative()
    {
        $this->fillTable();

        $registerUser = [
            'login' => '7999888775512-'
        ];

        $this->post('/api/v1/get_verification_code', $registerUser);

        $this->assertDatabaseCount('auth.users', 3);
    }

    public function testGetAuthorization()
    {
        $this->fillTable();

        $registerUser = [
            'login' => '79998887766',
            'verification_code' => '123456'
        ];

        $response = $this->post('/api/v1/get_authorization', $registerUser);

        $response->assertOk();
    }

    public function testGetAuthorizationNegative()
    {
        $this->fillTable();

        $registerUser = [
            'login' => '79998887766',
            'verification_code' => '123451'
        ];

        $response = $this->post('/api/v1/get_authorization', $registerUser);

        $response->assertForbidden();
    }

    public function testRegisterAndLoginAnonymous()
    {
        $this->fillTable();

        $response = $this->post('/api/v1/register_anonymous', ['session_id' => 's5a2bu4nr4f6f6ls8rls4pvt37']);

        $response->assertOk();
        $this->assertDatabaseCount('auth.users', 4);
    }
}
