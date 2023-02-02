<?php

namespace App\Modules\Authorization\Services;

use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param $accessToken
     * @return User
     */
    public function getUserByAccessToken($accessToken): User
    {
        $accessTokenParts = explode('.', $accessToken);
        $accessTokenBody = $accessTokenParts[1];
        $accessTokenBody = base64_decode($accessTokenBody);
        $accessTokenBody = json_decode($accessTokenBody, true);
        $userAccessToken = $accessTokenBody['jti'];
        $accessTokenId = $this->userRepository->getUserIdByAccessToken($userAccessToken);

        return $this->userRepository->getUserId($accessTokenId->user_id);
    }
}
