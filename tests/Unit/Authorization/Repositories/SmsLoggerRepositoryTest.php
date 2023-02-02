<?php

namespace Tests\Unit\Authorization\Repositories;

use App\Modules\Authorization\DTOs\SuccessSmsResponseDto;
use App\Modules\Authorization\Repositories\SmsLoggerRepository;
use Tests\TestCase;

class SmsLoggerRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateSuccess()
    {
        $dto = new SuccessSmsResponseDto(1, 1, 1, 1, 'blabla');
        $smsLoggerRepository = new SmsLoggerRepository();
        $smsLoggerRepository->create($dto);

        $this->assertDatabaseHas('sms_log', ['message_id' => '1']);
    }
}
