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
        Schema::create('payments.yandex_orders', function (Blueprint $table) {
            $table->string('yandex_order_id')->primary();
            $table->bigInteger('transaction_id');
            $table->string('checkout_url');
            $table->timestamps();

            $table
                ->foreign('transaction_id')
                ->references('id')->on('payments.transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.yandex_orders');
    }
};
