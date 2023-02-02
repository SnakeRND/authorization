<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments.merchants', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
        });

        $defaultMerchantCodes = ['cash', 'mkb', 'yandex'];

        foreach ($defaultMerchantCodes as $merchantCode) {
            DB::table('payments.merchants')->insert([
                'code' => $merchantCode
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.merchants');
    }
};
