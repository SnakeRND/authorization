<?php

namespace Tests\Unit\Authorization\Services;

use App\Modules\Authorization\ExternalServices\SmsSender;
use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\PassportRepository;
use App\Modules\Authorization\Repositories\UserRepository;
use App\Modules\Authorization\Services\ApiAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class ApiAuthServiceTest extends TestCase
{
    private PassportRepository $passportRepository;
    private Request $request;
    private UserRepository $userRepository;
    private SmsSender $smsSender;

    protected function setUp(): void
    {
        $this->passportRepository = Mockery::mock(PassportRepository::class);
        $this->request = Mockery::mock(Request::class);
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->smsSender = Mockery::mock(SmsSender::class);

        parent::setUp();
    }

    public function testGetUserByLogin()
    {
        $this->fillTable();
        $login = '79998887766';

        $this->userRepository->shouldReceive('getUserByLogin')
            ->once()->with($login)
            ->andReturn(User::where('login', $login)->first());

        $apiAuthService = new ApiAuthService(
            $this->passportRepository,
            $this->request,
            $this->userRepository,
            $this->smsSender
        );
        $apiAuthService->getUserByLogin($login);
    }

    public function testGetUserByLoginNegative()
    {
        $this->fillTable();
        $login = '79998887765';

        $this->userRepository->shouldReceive('getUserByLogin')
            ->once()->with($login)
            ->andReturn(User::where('login', $login)->first());

        $apiAuthService = new ApiAuthService(
            $this->passportRepository,
            $this->request,
            $this->userRepository,
            $this->smsSender
        );
        $result = $apiAuthService->getUserByLogin($login);

        $this->assertEquals(null, $result);
    }

    public function testSaveUser()
    {
        $this->fillTable();
        $user = User::where('login', '79998887766')->first();

        $this->userRepository->shouldReceive('save')
            ->once()->with($user)
            ->andReturn($user);
        $apiAuthService = new ApiAuthService(
            $this->passportRepository,
            $this->request,
            $this->userRepository,
            $this->smsSender
        );
        $apiAuthService->saveUser($user);
    }

    private function fillTable()
    {
        User::factory()->create([
            'login' => '79998887766',
            'password' => Hash::make('12131313'),
            'verification_code' => '707707'
        ]);
    }
}
