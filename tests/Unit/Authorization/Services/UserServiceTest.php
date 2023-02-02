<?php

namespace Tests\Unit\Authorization\Services;

use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\UserRepository;
use App\Modules\Authorization\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->userRepository = Mockery::mock(UserRepository::class);

        parent::setUp();
    }

    private function fillTable()
    {
        User::factory()->create([
            'login' => '79998887766',
            'password' => Hash::make('12131313'),
            'verification_code' => '707707'
        ]);

        User::factory()->create([
            'login' => '79998887777',
            'password' => Hash::make('12131313'),
            'verification_code' => '707707'
        ]);
    }

    public function testGetUserByLogin()
    {
        $this->fillTable();
        $login = '79998887766';

        $this->userRepository->shouldReceive('getUserByLogin')
            ->once()->with($login)
            ->andReturn(User::where('login', $login)->first());

        $userService = new UserService($this->userRepository);
        $userService->getUserByLogin($login);
    }

    public function testGetUserByLoginNegative()
    {
        $this->fillTable();
        $login = '79998887788';

        $this->userRepository->shouldReceive('getUserByLogin')
            ->once()->with($login)
            ->andReturn(User::where('login', $login)->first());

        $userService = new UserService($this->userRepository);
        $result = $userService->getUserByLogin($login);

        $this->assertEquals(null, $result);
    }
}
