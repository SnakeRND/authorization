<?php

namespace App\Modules\Authorization\DTOs;


use App\Modules\Authorization\Interfaces\SmsSenderResponseInterface;

class ErrorHttpResponseDto implements SmsSenderResponseInterface
{
    private $body;

    /**
     * @param array $body
     */
    public function __construct(array $body)
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }
}
