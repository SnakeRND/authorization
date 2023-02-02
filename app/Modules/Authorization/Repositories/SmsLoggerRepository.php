<?php

namespace App\Modules\Authorization\Repositories;

use App\Modules\Authorization\DTOs\SuccessSmsResponseDto;
use App\Modules\Authorization\Interfaces\SmsSenderResponseInterface;
use App\Modules\Authorization\Models\SmsLog;

class SmsLoggerRepository
{
    /**
     * @param SmsSenderResponseInterface $dto
     */
    public function create(SmsSenderResponseInterface $dto): void
    {
        $errorNum = $dto instanceof SuccessSmsResponseDto ? 0 : $dto->getErrorNum();
        $smsLogs = new SmsLog();
        $smsLogs->error_num = $errorNum;
        $smsLogs->message_id = $dto->getMessageId();
        $smsLogs->phone = $dto->getMessageDestination();
        $smsLogs->message = $dto->getMessage();
        $smsLogs->save();
    }
}
