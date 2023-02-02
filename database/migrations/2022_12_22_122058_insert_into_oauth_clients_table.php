<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('oauth_clients')->insert(
            [
                'id' => '97ec2171-ff9c-4033-a49c-423736eca636',
                'user_id' => null,
                'name' => 'Laravel Personal Access Client',
                'secret' => 'EIj6bGr2ncQO2qItgr0buh2jj9RsYag0smKWUlkk',
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('oauth_clients')->insert(
            [
                'id' => '97ec2172-0f2c-4723-beb1-ab1da63353de',
                'user_id' => null,
                'name' => 'Laravel Password Grant Client',
                'secret' => 'pNkKEOUcIOzqzsfpFXkUiwQyBOqKF5m6k25pdyFd',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DELETE FROM auth.oauth_clients');
    }
};
