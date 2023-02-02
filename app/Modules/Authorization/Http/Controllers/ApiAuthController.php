<?php

namespace App\Modules\Authorization\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserSaveException;
use App\Http\Controllers\Controller;
use App\Modules\Authorization\Http\Requests\UserAnonymousRegisterRequest;
use App\Modules\Authorization\Http\Requests\UserCodeRequest;
use App\Modules\Authorization\Http\Requests\UserLoginRequest;
use App\Modules\Authorization\Http\Requests\UserRefreshRequest;
use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Services\ApiAuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ApiAuthController extends Controller
{
    public function __construct(
        private ApiAuthService $apiAuthService
    ) {
    }

    /**
     * Принимает номер телефона, отправляет смс и создает юзера, если с таким номером еще нет
     *
     * @param UserCodeRequest $userCodeRequest
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function getVerificationCode(UserCodeRequest $userCodeRequest): JsonResponse
    {
        return response()->json($this->apiAuthService->sendSmsAndCreateUser($userCodeRequest));
    }

    /**
     * Принимает номер телефона, код верификации и авторизует пользователя
     *
     * @param UserLoginRequest $userLoginRequest
     * @return JsonResponse
     * @throws Exception
     */
    public function getAuthorization(UserLoginRequest $userLoginRequest): JsonResponse
    {
        $response = $this->apiAuthService->getAuthorization($userLoginRequest);
        $status = '200';
        if (array_key_exists('status', $response)) {
            $status = $response['status'];
        }

        return response()->json($response, $status);
    }

    /**
     * Принимает Refresh-токен и возвращает обновленный Oauth-токен
     *
     * @param UserRefreshRequest $userRefreshRequest
     * @return JsonResponse
     * @throws Exception
     */
    public function getOauthTokenByRefresh(UserRefreshRequest $userRefreshRequest): JsonResponse
    {
        return response()->json($this->apiAuthService->getOauthTokenByRefresh($userRefreshRequest));
    }

    /**
     * Создает, авторизует анонимного пользователя, возвращает его uuid и токен
     *
     * @param UserAnonymousRegisterRequest $userAnonymousRegisterRequest
     * @return JsonResponse
     * @throws UserSaveException
     * @throws Exception
     */
    public function registerAndLoginAnonymous(UserAnonymousRegisterRequest $userAnonymousRegisterRequest): JsonResponse
    {
        $login = $userAnonymousRegisterRequest['session_id'];
        $userLoginRequest = new UserLoginRequest();

        $existUser = $this->apiAuthService->getUserByLogin($login);
        if ($existUser) {
            return response()->json([
                'success' => true,
                'anonymous_user_uuid' => $existUser->id,
                'message' => 'Anonymous user already exist'
            ]);
        }

        $user = new User();
        $user->login = $login;
        $password = $user->login . $this->apiAuthService->bodyUserPassword;
        $user->password = Hash::make($password);
        $user->is_anonymous_user = true;
        $user->verification_code = $this->apiAuthService->anonymousVerificationCode;
        $this->apiAuthService->saveUser($user);

        $userLoginRequest->login = $login;
        $userLoginRequest->password = $password;
        $userLoginRequest->verification_code = $this->apiAuthService->anonymousVerificationCode;

        $userLoginRequest->request->add([
            'login' => $login,
            'verification_code' => $this->apiAuthService->anonymousVerificationCode
        ]);

        return response()->json([
            'anonymous_user_uuid' => $user->id,
            'tokens' => $this->apiAuthService->getOauthToken($userLoginRequest)
        ]);
    }

    public function testIp()
    {
        return json_decode(
            Http::monolith()->get('/test_ip')->body(),
            1
        );
    }
}
