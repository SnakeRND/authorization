<?php

namespace App\Modules\Authorization\DTOs;

use App\Modules\Authorization\Interfaces\SmsSenderResponseInterface;

class SmsResponseDto
{
    /**
     * @var SmsSenderResponseInterface
     */
    private $body;
    /**
     * @var int
     */
    private $statusCode;

    public function __construct(SmsSenderResponseInterface $body, int $statusCode)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    /**
     * @return SmsSenderResponseInterface
     */
    public function getBody(): SmsSenderResponseInterface
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
