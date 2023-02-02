<?php

namespace App\Modules\Authorization\ExternalServices;


use App\Modules\Authorization\DTOs\ErrorHttpResponseDto;
use App\Modules\Authorization\DTOs\ErrorSmsResponseDto;
use App\Modules\Authorization\DTOs\SmsResponseDto;
use App\Modules\Authorization\DTOs\SuccessSmsResponseDto;
use App\Modules\Authorization\Interfaces\SmsSenderInterface;
use App\Modules\Authorization\Repositories\SmsLoggerRepository;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

use function config;

class SmsSender implements SmsSenderInterface
{
    private object $config;

    public function __construct(private SmsLoggerRepository $smsLogger)
    {
        $this->config = (object)config('sms.intellin');
    }

    /**
     * @param string $phone
     * @param string $message
     * @return SmsResponseDto
     */
    public function send(string $phone, string $message): SmsResponseDto
    {
        $url = $this->config->domain . '/sendsms.cgi';

        $url = $url .
            '?http_username=' . $this->config->username .
            '&http_password=' . $this->config->password .
            '&phone_list=' . $phone .
            '&message=' . urlencode($message) .
            '&format=' . 'json';

        $result = $this->sendCurlRequest($url);

        $body = new ErrorHttpResponseDto($result[1]);

        if ($result[0] === 0) {
            if ((int)$result[1]['error_num'] === 0) {
                $body = new SuccessSmsResponseDto(
                    $result[1]['serial_id'],
                    $result[1]['message_id'],
                    $result[1]['message_destination'],
                    $result[1]['message_parts'],
                    $message
                );
            } else {
                $body = new ErrorSmsResponseDto(
                    $result[1]['serial_id'],
                    $result[1]['error_num'],
                    $result[1]['message_id'],
                    $result[1]['message_destination'],
                    $result[1]['message_parts'],
                    $message
                );
            }

            $this->smsLogger->create($body);
        }

        return new SmsResponseDto($body, $result[0]);
    }

    /**
     * @param string $url
     * @return array
     */
    private function sendCurlRequest(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        try {
            $response = Http::get($url);
            //TODO Удалить логирование после дебага
            Log::debug($response);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response = json_decode($response, 1);
        } catch (\Exception $e) {
            $message = "Запрос на отправку СМС. Получен пустой ответ или ошибка при выполнении запроса. \n";
            $message .= "Текст ошибки: " . curl_error($ch) . "\n";
            $message .= "Код ошибки: " . curl_errno($ch) . "\n";
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            return [$httpCode, $message];
        }

        return [$httpCode, $response];
    }
}
