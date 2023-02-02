<?php

namespace App\Modules\Authorization\DTOs;

use App\Modules\Authorization\Interfaces\SmsSenderResponseInterface;

class ErrorSmsResponseDto implements SmsSenderResponseInterface
{
    /**
     * @var string
     */
    private $serialId;
    /**
     * @var string
     */
    private $errorNum;
    /**
     * @var string
     */
    private $messageId;
    /**
     * @var string
     */
    private $messageDestination;
    /**
     * @var string
     */
    private $messageParts;
    /**
     * @var string
     */
    private $message;

    public function __construct(
        string $serialId,
        string $errorNum,
        string $messageId,
        string $messageDestination,
        string $messageParts,
        string $message
    ) {
        $this->serialId = $serialId;
        $this->errorNum = $errorNum;
        $this->messageId = $messageId;
        $this->messageDestination = $messageDestination;
        $this->messageParts = $messageParts;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSerialId(): string
    {
        return $this->serialId;
    }

    /**
     * @return string
     */
    public function getErrorNum(): string
    {
        return $this->errorNum;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getMessageDestination(): string
    {
        return $this->messageDestination;
    }

    /**
     * @return string
     */
    public function getMessageParts(): string
    {
        return $this->messageParts;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
