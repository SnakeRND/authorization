<?php

namespace App\Modules\Authorization\DTOs;


use App\Modules\Authorization\Interfaces\SmsSenderResponseInterface;

class GetSmsStatusResponseDto implements SmsSenderResponseInterface
{
    /**
     * @var int
     */
    private $serialId;

    /**
     * @var int
     */
    private $errorNum;
    /**
     * @var int
     */
    private $messageId;
    /**
     * @var int
     */
    private $messageParts;
    /**
     * @var string
     */
    private $sendDate;
    /**
     * @var string
     */
    private $deliverDate;
    /**
     * @var string
     */
    private $dateSend;
    /**
     * @var string
     */
    private $dateStatus;
    /**
     * @var string
     */
    private $deliverStatus;
    /**
     * @var string
     */
    private $errorCode;

    public function __construct(
        int $serialId,
        int $errorNum,
        int $messageId,
        int $messageParts,
        string $sendDate,
        string $deliverDate,
        string $dateSend,
        string $dateStatus,
        string $deliverStatus,
        string $errorCode
    ) {
        $this->serialId = $serialId;
        $this->errorNum = $errorNum;
        $this->messageId = $messageId;
        $this->messageParts = $messageParts;
        $this->sendDate = $sendDate;
        $this->deliverDate = $deliverDate;
        $this->dateSend = $dateSend;
        $this->dateStatus = $dateStatus;
        $this->deliverStatus = $deliverStatus;
        $this->errorCode = $errorCode;
    }

    /**
     * @return int
     */
    public function getSerialId(): int
    {
        return $this->serialId;
    }

    /**
     * @return int
     */
    public function getErrorNum(): int
    {
        return $this->errorNum;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @return int
     */
    public function getMessageParts(): int
    {
        return $this->messageParts;
    }

    /**
     * @return string
     */
    public function getSendDate(): string
    {
        return $this->sendDate;
    }

    /**
     * @return string
     */
    public function getDeliverDate(): string
    {
        return $this->deliverDate;
    }

    /**
     * @return string
     */
    public function getDateSend(): string
    {
        return $this->dateSend;
    }

    /**
     * @return string
     */
    public function getDateStatus(): string
    {
        return $this->dateStatus;
    }

    /**
     * @return string
     */
    public function getDeliverStatus(): string
    {
        return $this->deliverStatus;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
