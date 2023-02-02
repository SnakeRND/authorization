<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth.users', function (Blueprint $table) {
            $table->bigInteger('monolith_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('token', 255)->nullable();
            $table->string('clientid_1C', 255)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth.users', function (Blueprint $table) {
            $table->dropColumn('monolith_id');
            $table->dropColumn('role_id');
            $table->dropColumn('token');
            $table->dropColumn('clientid_1C');
        });
    }
};
