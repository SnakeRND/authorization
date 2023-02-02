<?php

namespace Tests\Feature\Authorization\Http\Controllers;


use App\Modules\Authorization\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

    public function testProfile()
    {
        $this->fillTable();

        $user = [
            'login' => '79998887766'
        ];

        $response = $this->post('/api/v1/user/profile', $user);

        $response->assertOk();
    }

    public function testProfileNegative()
    {
        $this->fillTable();

        $user = [
            'login' => '79998887799'
        ];

        $response = $this->post('/api/v1/user/profile', $user);
        
        $response->assertServerError();
    }
}
