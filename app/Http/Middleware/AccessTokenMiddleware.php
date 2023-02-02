<?php

namespace App\Http\Middleware;

use App\Exceptions\ValidationException;
use App\Modules\Authorization\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccessTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return Response|RedirectResponse
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = str_replace('Bearer ', '', $request->header('authorization'));

        if (!$accessToken) {
            throw new ValidationException(json_encode(["access_token" => ["access_token not found"]]));
        }

        if (!$this->getUserByAccessToken($accessToken)) {
            throw new ValidationException(
                json_encode(["access_token" => ["user with current access_token not found"]])
            );
        }

        return $next($request);
    }

    /**
     * @param $accessToken
     * @return User
     */
    private function getUserByAccessToken($accessToken): User
    {
        $accessTokenParts = explode('.', $accessToken);
        $accessTokenBody = $accessTokenParts[1];
        $accessTokenBody = base64_decode($accessTokenBody);
        $accessTokenBody = json_decode($accessTokenBody, true);
        $userAccessToken = $accessTokenBody['jti'];
        $accessTokenId = $this->getUserIdByAccessToken($userAccessToken);

        return $this->getUserId($accessTokenId->user_id);
    }

    /**
     * @param $userAccessToken
     * @return object
     */
    private function getUserIdByAccessToken($userAccessToken): object
    {
        return DB::table('oauth_access_tokens')->where('id', $userAccessToken)->first();
    }

    /**
     * @param $id
     * @return User|null
     */
    private function getUserId($id): User|null
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return null;
        }

        return $user;
    }
}
