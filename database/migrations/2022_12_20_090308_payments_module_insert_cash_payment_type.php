<?php

use App\Packages\Enums\MerchantCodeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $merchantId = DB::table('payments.merchants')
            ->where('code', MerchantCodeEnum::CASH)
            ->pluck('id')
            ->first();

        DB::table('payments.payment_types')->insert([
            'merchant_id' => $merchantId,
            'name' => 'Оплата при получении',
            'enabled' => true
        ]);
    }

    public function down()
    {
    }
};
