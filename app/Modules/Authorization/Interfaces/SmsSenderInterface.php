<?php

namespace App\Modules\Authorization\Interfaces;


use App\Modules\Authorization\DTOs\SmsResponseDto;

interface SmsSenderInterface
{
    /**
     *
     * Отправлеяет запрос на отправку СМС-сообщения провайдеру
     *
     * @param string $phone
     * @param string $message
     * @return SmsResponseDto
     */
    public function send(string $phone, string $message): SmsResponseDto;
}
