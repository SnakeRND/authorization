<?php

namespace App\Modules\Authorization\Services;

use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserSaveException;
use App\ExternalServices\UviMonolithService;
use App\Modules\Authorization\ExternalServices\SmsSender;
use App\Modules\Authorization\Http\Requests\UserCodeRequest;
use App\Modules\Authorization\Http\Requests\UserLoginRequest;
use App\Modules\Authorization\Http\Requests\UserRefreshRequest;
use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\PassportRepository;
use App\Modules\Authorization\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class ApiAuthService
{
    public string $anonymousVerificationCode;
    public string $bodyUserPassword;

    public function __construct(
        private PassportRepository $passportRepository,
        private Request $request,
        private UserRepository $userRepository,
        private UserService $userService,
        private SmsSender $smsSender
    ) {
        $this->anonymousVerificationCode = '7077';
        $this->bodyUserPassword = '#mEYGh}oX@pa';
    }

    /**
     * Отправляет запрос на получение oauth токена
     *
     * @param UserLoginRequest $userLoginRequest
     * @return array
     * @throws Exception
     */
    public function getOauthToken(UserLoginRequest $userLoginRequest): array
    {
        $request = $userLoginRequest->request->all();
        $passwordGrantClient = $this->getPasswordGrantClient();

        if ($passwordGrantClient) {
            $login = array_key_exists('login', $request) ? $request['login'] : $userLoginRequest->login;

            $data = [
                'grant_type' => 'password',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'username' => $login,
                'password' => $login . $this->bodyUserPassword,
                'scope' => '*'
            ];

            return json_decode($this->getTokens($data), true);
        }

        return [
            'success' => false,
            'message' => 'Oauth client not found'
        ];
    }

    /**
     * Отправляет запрос на обновление oauth токена
     *
     * @param UserRefreshRequest $userRefreshRequest
     * @return array
     * @throws Exception
     */
    public function getOauthTokenByRefresh(UserRefreshRequest $userRefreshRequest): array
    {
        $passwordGrantClient = $this->getPasswordGrantClient();

        if ($passwordGrantClient) {
            $refresh_token = $userRefreshRequest['refresh_token'] ? $userRefreshRequest['refresh_token'] : (string)$userRefreshRequest->refresh_token;

            $data = [
                'grant_type' => 'refresh_token',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'refresh_token' => $refresh_token,
                'scope' => '*'
            ];

            $existAnonymousUser = $this->userService->getUserByAccessToken(
                $userRefreshRequest->header('authorization')
            );

            $userUuid = '';

            $tokens = json_decode($this->getTokens($data), true);

            if ($existAnonymousUser->is_anonymous_user) {
                $this->sendTokensToMonolith($existAnonymousUser->id, $userUuid, $tokens);
            }

            return $tokens;
        }

        return [
            'success' => false,
            'message' => 'Oauth client not found'
        ];
    }

    /**
     * Принимает номер телефона, код верификации и авторизует пользователя
     *
     * @param UserLoginRequest $userLoginRequest
     * @return array
     * @throws Exception
     */
    public function getAuthorization(UserLoginRequest $userLoginRequest): array
    {
        $user = $this->userRepository->getUserByLogin($userLoginRequest['login']);

        if (!$user) {
            return ['success' => false, 'message' => 'User not found', 'status' => '500'];
        }

        if ((string)$userLoginRequest['verification_code'] === $user->verification_code) {
            $userLoginRequest->request->add(['verification_code' => $user->verification_code]);
            $tokens = $this->getOauthToken($userLoginRequest);

            $existAnonymousUser = $this->userService->getUserByAccessToken(
                $userLoginRequest->header('authorization')
            );

            if ($existAnonymousUser->is_anonymous_user) {
                $this->sendTokensToMonolith($existAnonymousUser->id, $user->id, $tokens);
            }

            return $tokens;
        }

        return ['success' => false, 'message' => 'Wrong verification code', 'status' => '500'];
    }

    /**
     * @param $userCodeRequest
     * @return array
     * @throws UserNotFoundException
     */
    public function sendSmsAndCreateUser($userCodeRequest): array
    {
        try {
            $verification_code = $this->sendSms($userCodeRequest);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $existUser = $this->userRepository->getUserByLogin($userCodeRequest->login);

        if (!$existUser) {
            $this->userRepository->createUser($userCodeRequest->login, $verification_code, $this->bodyUserPassword);
        } else {
            $existUser->verification_code = $verification_code;
            $existUser->update();
        }

        return ['success' => true, 'message' => 'Verification code sent successfully'];
    }

    /**
     * @throws UserSaveException
     */
    public function saveUser($user)
    {
        $this->userRepository->save($user);
    }

    /**
     * @param $login
     * @return User|null
     * @throws UserNotFoundException
     */
    public function getUserByLogin($login): User|null
    {
        return $this->userRepository->getUserByLogin($login);
    }

    /**
     * @param $uuid
     * @param $tokens
     * @return void
     */
    public function sendTokensToMonolith($anonymousUuid, $userUuid, $tokens): void
    {
        Http::withToken(env('UVI_MONOLITH_TOKEN'))
            ->get(UviMonolithService::DOMAIN . '/update_access_tokens_monolith', [
                    'branch' => 'feature.sandreev',
                    'checkout_user_id_old' => $anonymousUuid,
                    'checkout_user_id_new' => $userUuid,
                    'access_token' => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                ]
            );
    }

    /**
     * Генерирует код верификации и отправляет смс
     *
     * @param UserCodeRequest $userCodeRequest
     * @return int
     */
    private function sendSms(UserCodeRequest $userCodeRequest): int
    {
        $verification_code = mt_rand(1000, 9999);
        $message = 'Код подтверждения: ' . $verification_code;

        $this->smsSender->send($userCodeRequest['login'], $message);

        return $verification_code;
    }

    /**
     * @return Client|bool
     */
    private function getPasswordGrantClient(): Client|bool
    {
        return $this->passportRepository->getPasswordGrantClient();
    }

    /**
     * @throws Exception
     */
    private function getTokens($data): bool|string
    {
        $tokenRequest = $this->request->create('/oauth/token', 'post', $data);
        $tokenResponse = app()->handle($tokenRequest);

        return $tokenResponse->getContent();
    }
}
