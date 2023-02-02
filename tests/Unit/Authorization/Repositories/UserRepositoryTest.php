<?php

namespace Tests\Unit\Authorization\Repositories;

use App\Exceptions\UserNotFoundException;
use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = app(UserRepository::class);
        parent::setUp();
    }

    private function fillUserTable()
    {
        User::factory()->create([
            'login' => '79998887777',
            'password' => Hash::make('12131313'),
            'monolith_id' => 100,
            'verification_code' => '707707'
        ]);

        User::factory()->create([
            'login' => '79998887766',
            'password' => Hash::make('232323233'),
            'monolith_id' => 200,
            'verification_code' => '808808'
        ]);

        User::factory()->create([
            'login' => '79998887744',
            'password' => Hash::make('232323233'),
            'monolith_id' => 300,
            'verification_code' => '808808'
        ]);
    }

    public function testSave()
    {
        $this->fillUserTable();

        $user = new User;
        $user->login = '79998887755';
        $user->password = Hash::make('333333333');
        $user->verification_code = '333333';

        $this->userRepository->save($user);

        $this->assertDatabaseHas('auth.users', ['login' => '79998887755']);
    }

    public function testCreateUser()
    {
        $this->fillUserTable();

        $login = '79998887755';
        $verification_code = '333333';
        $bodyUserPassword = 'qddascef';

        $this->userRepository->createUser($login, $verification_code, $bodyUserPassword);

        $this->assertDatabaseHas('auth.users', ['login' => '79998887755']);
    }

    public function testGetUserByLogin()
    {
        $this->fillUserTable();
        $login = '79998887777';

        $user = User::where('login', '79998887777')->first();
        $foundUser = $this->userRepository->getUserByLogin($login);

        $this->assertEquals($user, $foundUser);
    }

    public function testGetUserByLoginException()
    {
        $this->fillUserTable();
        $login = '79998887711';

        $this->expectException(UserNotFoundException::class);
        $this->userRepository->getUserByLogin($login);
    }

    public function testGetUserByMonolithId()
    {
        $this->fillUserTable();
        $monolithId = 100;

        $user = User::where('monolith_id', 100)->first();
        $foundUser = $this->userRepository->getUserByMonolithId($monolithId);

        $this->assertEquals($user, $foundUser);
    }

    public function testGetUserByMonolithIdException()
    {
        $this->fillUserTable();
        $monolithId = 400;

        $this->expectException(UserNotFoundException::class);
        $this->userRepository->getUserByLogin($monolithId);
    }
}
