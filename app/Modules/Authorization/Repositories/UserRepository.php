<?php

namespace App\Modules\Authorization\Repositories;


use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserSaveException;
use App\Modules\Authorization\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param User $user
     * @return User
     * @throws UserSaveException
     */
    public function save(User $user): User
    {
        $result = $user->save();
        if (!$result) {
            throw new UserSaveException();
        }

        return $user;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * @param $login
     * @param $verification_code
     * @param $bodyUserPassword
     * @return void
     */
    public function createUser($login, $verification_code, $bodyUserPassword): void
    {
        $user = new User;
        $user->login = $login;
        $user->password = Hash::make(
            $login . $bodyUserPassword
        );
        $user->verification_code = $verification_code;
        $user->save();
    }

    /**
     * @param $login
     * @return User|null
     * @throws UserNotFoundException
     */
    public function getUserByLogin($login): User|null
    {
        $user = User::where('login', $login)->first();
        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * @param $monolithId
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserByMonolithId($monolithId): User
    {
        $user = User::where('monolith_id', $monolithId)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getUserId($id): User|null
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * @param $userAccessToken
     * @return object
     */
    public function getUserIdByAccessToken($userAccessToken): object
    {
        return DB::table('oauth_access_tokens')->where('id', $userAccessToken)->first();
    }
}
