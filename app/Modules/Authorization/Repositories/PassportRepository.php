<?php

namespace App\Modules\Authorization\Repositories;

use Laravel\Passport\Client;

class PassportRepository
{
    public function __construct(
        private Client $client
    ) {
    }

    /**
     * @return Client|bool
     */
    public function getPasswordGrantClient(): Client|bool
    {
        $result = $this->client->where('password_client', 1)->first();

        if ($result) {
            return $result;
        }

        return false;
    }
}
